<?php

namespace Modules\Shipping\Entities;

use App\Models\ModelCompoundPrimaryKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShippingMethodCountry extends ModelCompoundPrimaryKey
{
    use HasFactory;
    public $table ='shipping_method_countries';

    protected $fillable = [
        'shipping_method_id',
        'country_id',
        'expense',
        'discount',
    ];
    protected $primaryKey = ['shipping_method_id', 'country_id'];
    
    protected static function newFactory()
    {
        return \Modules\Shipping\Database\factories\ShippingMethodCountryFactory::new();
    }
    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id');
    }
}
