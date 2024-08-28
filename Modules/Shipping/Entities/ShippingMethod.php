<?php

namespace Modules\Shipping\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShippingMethod extends Model
{
    use HasFactory;
    public $table = 'shipping_methods';
    protected $fillable = [
        'shipping_method_name',
        'expense',
        'discount',
        'delivery_time',
        'default',
    ];
    protected $casts = [
        'default' => 'boolean',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Shipping\Database\factories\ShippingMethodFactory::new();
    }
    public function countries()
    {
        return $this->belongsToMany(Country::class, 'shipping_method_countries', 'shipping_method_id', 'country_id');
    }
    public function shippingMethodCountries()
    {
        return $this->hasMany(ShippingMethodCountry::class, 'shipping_method_id');
    }
}
