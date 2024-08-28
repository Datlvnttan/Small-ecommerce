<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BillingAddress extends Address
{
    use HasFactory;
    public $table = 'billing_addresses';

    protected $fillable = [
        'user_id',
        'fullname',
        'country_id',
        'province', //mã tỉnh/thành phố
        'district', //mã quận/huyện
        'ward', //mã xã
        'zip_code', //mã bưu điện
        'address_specific',
        'tax_id_number',
        'default',
    ];
    protected $casts = [
        'tax_id_number' => 'string',
        'default' => 'boolean',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->appends =  array_merge($this->appends, []);
    }
    protected static function newFactory()
    {
        return \Modules\User\Database\factories\BillingAddressFactory::new();
    }
    public function country()
    {
        return $this->belongsTo(\Modules\Shipping\Entities\Country::class, 'country_id');
    }
}
