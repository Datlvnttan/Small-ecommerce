<?php

namespace Modules\Mailer\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Modules\Mailer\Emails\AutomaticMassEmail;
use Modules\Mailer\Jobs\AutomaticMassEmailJob;
use Modules\User\Entities\User;
use Modules\User\Repositories\Interface\UserRepositoryInterface;
use Illuminate\Support\Str;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Services\OrderService;

class MailService
{
    protected $userRepositoryInterface;
    public function __construct(UserRepositoryInterface $userRepositoryInterface = null)
    {
        $this->userRepositoryInterface = $userRepositoryInterface;
    }
    public function resendEmailVerify(string $id)
    {
        // $user = User::find($id);
        $user = $this->userRepositoryInterface->find($id);
        // return $user;
        if (isset($user)) {
            $otp_renew_at = Carbon::parse($user->otp_renew_at)->addMinutes();
            if ($otp_renew_at->greaterThan(now()))
                return false;
            $otp = str(rand(100000, 999999));
            $update = $this->userRepositoryInterface->update($id, [
                "OTP" => $otp,
                "otp_renew_at" => Carbon::now()
            ]);
            if ($update) {
                $this->sendEmailVerify($user->email, $user->fullname, $otp);
                return True;
            }
        }
        return False;
    }
    public function sendEmailVerify($email, $fullname, $otp)
    {
        AutomaticMassEmailJob::dispatch($email, $fullname, "Verify your email", "mailer::verify", [
            "otp" => $otp,
        ]);
    }
    public function sendNotificationOrderStatusEmail($order)
    {
        if (!isset($order)) {
            return false;
        }
        $subject = null;
        $contentView = null;
        $data = null;
        $currentStatus = $order->current_status->value;
        switch ($currentStatus) {
            case OrderStatus::Cancelled->value:
            case OrderStatus::Rejected->value:
                
                $subject = "Your order has been {$currentStatus}";
                $contentView = "mailer::notification-order-cancel";
                $data =  [
                    'title' => $subject,
                    'status' => $currentStatus,
                    'at' => $order->status[$currentStatus]['at'],
                    'reason' => $order->status[$currentStatus]['reason'],
                    'orderKey' => $order->order_key
                ];
                break;
            default:
                $subject = "Your order is in status {$currentStatus}";
                $contentView = "mailer::notification-order-status";
                $data =  [
                    'title' => $subject,
                    'status' => $currentStatus,
                    'orderKey' => $order->order_key
                ];
        }

        AutomaticMassEmailJob::dispatch($order->email, $order->delivery_address->fullname, $subject, $contentView, $data);
        return true;
    }
    public function sendOrderSuccessNotification($email, $fullname, $newOrder, $orderDetails)
    {
        $subject = null;
        $title = null;
        switch ($newOrder->current_status) {
            case OrderStatus::AwaitingConfirmation:
                $subject = 'Your order has been placed successfully';
                $title = 'Your order has been placed successfully';
                break;
            case OrderStatus::Processing:
                $subject = 'Your order has been successfully paid';
                $title = 'Your order has been successfully paid';
                break;
            default:

                break;
        }
        AutomaticMassEmailJob::dispatch($email, $fullname, $subject ?? "Your order has been successfully", "mailer::order-success", [
            'newOrder' => $newOrder,
            'orderDetails' => $orderDetails,
            'title' => $title,
        ]);
    }
    public function SendOrderConfirmationEmail($email, $fullname, $orderKey, $tokenOrderVerify)
    {
        AutomaticMassEmailJob::dispatch($email, $fullname, "Verify your order", "mailer::order-verify", [
            'url' => route('web.order.verify-order', [
                "orderKey" => $orderKey,
                'tokenOrderVerify' => $tokenOrderVerify
            ]) //. "?tokenOrderVerify={$tokenOrderVerify}",
        ]);
    }

    public function checkOTPCode($id, $otpCode)
    {
        $user = $this->userRepositoryInterface->find($id);
        if (isset($user)) {
            $otp_renew_at = Carbon::parse($user->otp_renew_at)->addMinutes(10);
            if ($otp_renew_at->greaterThan(now())) {
                if ($user->OTP === $otpCode) {
                    $this->userRepositoryInterface->update($id, [
                        "email_verified_at" => Carbon::now(),
                        "OTP" => null,
                        "otp_renew_at" => null
                    ]);
                    return 1; //successful authentication
                }
                return 0; //OTP code is incorrect
            }
            return -1; //OTP expired
        }
        return -2; //User not found
    }
    public function sendEmailVerifyCancelOrderGuest($email, $fullname, $orderId, $otp)
    {
        AutomaticMassEmailJob::dispatch($email, $fullname, "Confirm cancellation of your order", "mailer::order-cancel", [
            'otp' => $otp,
            // 'url' => route('web.order.cancelEnterOTP', ['orderKey' => $orderId])
        ]);
    }
    public function sendEmailChangeEmailUser($oldEmail, $newEmail, $fullname, $token)
    {
        AutomaticMassEmailJob::dispatch($newEmail, $fullname, "Confirm change email", "mailer::change-email", [
            'url' => route(
                'web.user.verifyChangeEmail',
                [
                    'oldEmail' => $oldEmail,
                    'tokenChangeEmail' => $token
                ]
            )
        ]);
    }
}
