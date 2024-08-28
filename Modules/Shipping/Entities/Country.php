<?php

namespace Modules\Shipping\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory;
    public $table = 'countries';

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Shipping\Database\factories\CountryFactory::new();
    }
    public function shippingMethods()
    {
        return $this->belongsToMany(ShippingMethod::class, 'shipping_method_countries', 'country_id', 'shipping_method_id')
        ->as('shipping_method_countries')
        ->withPivot(
            'expense as shipping_method_country_expense', 
            'discount as shipping_method_country_discount',
            'delivery_time as shipping_method_country_delivery_time'
    );
    }
}
