<?php

namespace Modules\User\Entities;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Modules\User\Database\Factories\UserFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory;
    protected $notDelivery = null;
    protected $notBilling = null;
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id',
        'fullname',
        'nickname',
        'OTP',
        'phone_name',
        'email',
        'otp_renew_at',
        'provider_id',
        'password',
        'google_id',
        'email_verified_at'
    ];

    protected $hidden=[
        'password',
        'OTP',
        'otp_renew_at',
        'google_id',
        'remember_token',
        'updated_at',
        'email_verified_at',
        'provider_id',
        'token_change_email',
        'email_change_request',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'fullname' => 'string',
        'nickname' => 'string',
        'phone_name' => 'string',
        'password' => 'hashed',
        'newsletter_subscription' => 'boolean',
        'point_expiration_notification' => 'boolean',
    ];

    protected static function newFactory()
    {
        return \Modules\User\Database\factories\UserFactory::new();
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function deliveryAddresses()
    {
        return $this->hasMany(DeliveryAddress::class, 'user_id');
    }
    public function billingAddresses()
    {
        return $this->hasMany(BillingAddress::class, 'user_id');
    }
    public function deliveryAddressDefault()
    {
        return $this->hasOne(DeliveryAddress::class, 'user_id')->where('default',true);
    }
    public function isNotDeliveryAddress()
    {
        if (!isset($this->notDelivery)) {
            $this->notDelivery = $this->deliveryAddresses()->count() === 0;
        }
        return $this->notDelivery;
    }
    public function isNotBillingAddress()
    {
        if (!isset($this->notBilling)) {
            $this->notBilling = $this->billingAddresses()->count() === 0;
        }
        return $this->notBilling;
    }
}
