<?php

namespace Modules\Order\Entities;

use App\Helpers\Helper;
use App\Models\AccessControlModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Order\Enums\OrderStatus;
use Modules\User\Entities\User;

class Order extends AccessControlModel
{
    use HasFactory;

    public $table = 'orders';
    protected $fillable = [
        'user_id',
        'delivery_address',
        'payment_method',
        'shipping_method',
        'total_amount',
        'total_point',
        'status',
        'discount_coupon',
        'billing_address',
        'order_key',
        'email',
        'is_paid',
        'note'
    ];

    protected $casts = [
        'delivery_address' => 'json',
        'payment_method' => 'string',
        'shipping_method' => 'json',
        'total_amount' => 'double',
        'total_point' => 'integer',
        'status' => 'json',
        'discount_coupon' => 'json',
        'billing_address' => 'json',
        'order_key' => 'string',
        'email' => 'string',
        'note' => 'string',
        'is_paid' => 'boolean',
    ];
    // protected $hidden = [
    //     'email'
    // ];

    protected static function newFactory()
    {
        return \Modules\Order\Database\factories\OrderFactory::new();
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    public function getEmailAttribute()
    {
        if ($this->checkAccess() || !$this->isMasked) {
            return $this->attributes['email'];
        } else {
            if (isset($this->attributes['email'])) {
                return Helper::subMasked($this->attributes['email'], 3, '@');
            }
            return null;
        }
    }
    public function getDeliveryAddressAttribute()
    {
        $deliveryAddress = json_decode($this->attributes['delivery_address']);
        if ($this->checkAccess() || !$this->isMasked) {
            return $deliveryAddress;
        } else {
            if (isset($deliveryAddress)) {
                // return $deliveryAddress;
                return array_merge($this->getAddressMasked($deliveryAddress), [
                    'phone_number' => Helper::subMasked($deliveryAddress->phone_number, -3),
                ]);
            }
            return null;
        }
    }
    public function getBillingAddressAttribute()
    {
        $billingAddress = json_decode($this->attributes['billing_address']);
        if ($this->checkAccess() || !$this->isMasked) {
            return $billingAddress;
        } else {

            if (isset($billingAddress)) {
                // return $deliveryAddress;
                return array_merge($this->getAddressMasked($billingAddress), [
                    'tax_id_number' => Helper::subMasked($billingAddress->tax_id_number ?? null, -3),
                ]);
            }
            return null;
        }
    }

    protected function getAddressMasked($address)
    {
        return [
            'address_specific' => Helper::subMasked($address->address_specific ?? null, 3),
            'country' => Helper::subMasked($address->country ?? null, 3),
            'district' => Helper::subMasked($address->district ?? null, 3),
            'fullname' => Helper::subMasked($address->fullname ?? null, 3),
            'province' => Helper::subMasked($address->province ?? null, 3),
            'ward' => Helper::subMasked($address->ward ?? null, 3),
            'zip_code' => Helper::subMasked($address->zip_code ?? null, 3),
        ];
    }

    protected $appends = ['current_status','is_allowed_cancel','is_evaluate'];
    protected $current_status = null;
    /**
     * Summary of getCurrentStatusAttribute
     * @return OrderStatus|null
     */
    public function getCurrentStatusAttribute()
    {
        if (!isset($this->current_status)) {
            $statusArr = json_decode($this->attributes['status'], true);
            $this->current_status =  self::getStatus($statusArr);
            
        }

        return $this->current_status;
    }

    public static function getStatus($statusArr)
    {
        $current_status = null;
        if (is_array($statusArr)) {
            if (array_key_exists(OrderStatus::Cancelled->value, $statusArr)) {
                $current_status =  OrderStatus::Cancelled;
            } elseif (array_key_exists(OrderStatus::Rejected->value, $statusArr)) {
                $current_status =  OrderStatus::Rejected;
            } elseif (array_key_exists(OrderStatus::Delivered->value, $statusArr)) {
                $current_status =  OrderStatus::Delivered;
            } elseif (array_key_exists(OrderStatus::InTransit->value, $statusArr)) {
                $current_status =  OrderStatus::InTransit;
            } elseif (array_key_exists(OrderStatus::Processing->value, $statusArr)) {
                $current_status =  OrderStatus::Processing;
            } elseif (array_key_exists(OrderStatus::AwaitingConfirmation->value, $statusArr)) {
                $current_status =  OrderStatus::AwaitingConfirmation;
            } else {
                $current_status =  OrderStatus::AwaitingVerification;
            }
        } else {
            return null;
        }
        return $current_status;
    }

    public function setStatusAttribute($status)
    {
        $newStatus = $this->status;
        if ($status instanceof OrderStatus) {
            $newStatus[$status->value] = now();
        } else {
            $newStatus = array_merge($newStatus, $status);
        }
        $this->attributes['status'] = json_encode($newStatus);
        $this->current_status = null;
    }
    public function getDiscountCouponAttribute()
    {
        $disCountCoupon = $this->attributes['discount_coupon'];
        if(isset($disCountCoupon)) {
            return json_decode($disCountCoupon);
        }
        return false;
    }
    public function getIsEvaluateAttribute()
    {
        $currentStatus = $this->getCurrentStatusAttribute();
        return $this->checkAccess() && $currentStatus === OrderStatus::Delivered;
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function hideEmail()
    {
        return $this->makeHidden('email');
    }

    public function orderWithLogin()
    {
        return  isset($this->user_id);
    }
    public function getIsAllowedCancelAttribute()
    {
        $currentStatus = $this->getCurrentStatusAttribute();
        return $currentStatus === OrderStatus::AwaitingVerification
            || $currentStatus === OrderStatus::AwaitingConfirmation
            || ($currentStatus === OrderStatus::Processing && !$this->is_paid);
    }
}
