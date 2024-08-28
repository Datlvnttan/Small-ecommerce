<?php

namespace Modules\User\Services;

use App\Helpers\Helper;
use App\Helpers\ResponseJson;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Modules\Auth\Services\AuthService;
use Modules\Mailer\Services\MailService;
use Modules\User\Entities\User;
use Modules\User\Repositories\Interface\UserRepositoryInterface;
use Modules\User\Repositories\Repository\UserRepository;
use Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService
{
    protected $userRepo;
    protected $mailService;
    protected const TOKEN_UPDATE_EMAIL_PREFIX = 'update-email-';
    protected const TIME_OUT_TOKEN_CHANGE_EMAIL = 10 * 60;
    public function __construct(
        UserRepositoryInterface $userRepo,
        MailService $mailService
    ) {
        $this->userRepo = $userRepo;
        $this->mailService = $mailService;
    }
    public function findUserById($id)
    {
        return $this->userRepo->find($id);
    }
    public function update(User $user, array $data): int
    {
        return Helper::DBTransaction(function () use ($user, $data) {
            $user->fullname = $data['fullname'];
            $user->nickname = $data['nickname'];
            $checkChangeEmail = 2;
            if($user->email != $data['email'])
            {
                $user->email = $data['email'];
                $checkChangeEmail = 1;
            }
            if (isset($data['phoneNumber'])) {
                $user->phone_number = $data['phoneNumber'];
            } else {
                $user->phone_number = null;
            }
            if (isset($data['birthday'])) {
                $user->birthday = Carbon::parse($data['birthday']);
            } else {
                $user->birthday = null;
            }
            if (isset($data['gender'])) {
                $user->gender = $data['gender'];
            } else {
                $user->gender = null;
            }
            $user->save();
            return $checkChangeEmail;
        });
    }
    public function changePassword($user, $oldPassword, $newPassword)
    {
        if (!password_verify($oldPassword, $user->password)) {
            return false;
        }
        return Helper::DBTransaction(function () use ($user, $newPassword) {
            $user->password = $newPassword;
            $user->save();
            return true;
        });
    }
    protected function buildTokenChangeEmailUser($token)
    {
        return self::TOKEN_UPDATE_EMAIL_PREFIX . $token;
    }
    protected function getSessionChangeEmailUser($token)
    {
        return Session::get($this->buildTokenChangeEmailUser($token));
    }
    // protected function putSessionChangeEmailUser($token, $userId, $newEmail)
    // {
    //     Session::put($this->buildTokenChangeEmailUser($token), [
    //         'userId' => $userId,
    //         'newEmail' => $newEmail,
    //         'updatedAt' => Carbon::now()
    //     ]);
    // }
    // protected function removeSessionChangeEmailUser($token)
    // {
    //     return Session::remove($this->buildTokenChangeEmailUser($token));
    // }
    public function changeEmailUser($fullname, $originalEmail, $newEmail, $token)
    {
        $this->mailService->sendEmailChangeEmailUser($originalEmail, $newEmail, $fullname, $token);
    }
    public function confirmVerifyChangeEmail($tokenChangeEmail, $user)
    {
        if (!isset($user)) {
            return false;
        }
        if ($user->token_change_email === $tokenChangeEmail) {
            return Helper::DBTransaction(function () use ($user) {
                $observer = User::getEventDispatcher();
                User::unsetEventDispatcher();
                $user->email = $user->email_change_request;
                $user->token_change_email = null;
                $user->email_change_request = null;
                $user->save();
                User::setEventDispatcher($observer);
                return true;
            });
        }
        else{
            return false;
        }
    }
    public function findByEmail($email)
    {
        return $this->userRepo->findBy('email', $email);
    }
}
