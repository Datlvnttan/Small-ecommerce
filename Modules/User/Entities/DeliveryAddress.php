<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeliveryAddress extends Address
{
    use HasFactory;
    public $table = 'delivery_addresses';
    protected $fillable = [
        'user_id',
        'fullname',
        'country_id',
        'province', //mã tỉnh/thành phố
        'district', //mã quận/huyện
        'ward', //mã xã
        'zip_code', //mã bưu điện
        'address_specific',
        'international_calling_code',
        'phone_number',
        'default',
    ];

    protected $casts = [
        'international_calling_code' => 'string',
        'phone_number' => 'string',
        'default' => 'boolean',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->appends =  array_merge($this->appends, [
            'format_phone_number'
        ]);
    }

    protected static function newFactory()
    {
        return \Modules\User\Database\factories\DeliveryAddressFactory::new();
    }

    public function country()
    {
        return $this->belongsTo(\Modules\Shipping\Entities\Country::class, 'country_id');
    }
    public function internationalPhoneNumber()
    {
        $formattedPhoneNumber = preg_replace('/^0/', '', $this->phone_number);
        return $this->international_calling_code . $formattedPhoneNumber;
    }

    public function getFormatPhoneNumberAttribute()
    {
        return $this->internationalPhoneNumber();
    }
    // protected $appends = [''];
}
