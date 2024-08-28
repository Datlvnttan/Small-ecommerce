<?php

namespace Modules\Seller\Entities;

use App\Models\ModelCompoundPrimaryKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Product\Entities\Product;

class SellerProduct extends ModelCompoundPrimaryKey
{
    use HasFactory;
    public $table = 'seller_products';
    protected $primaryKey = ['seller_id', 'product_id'];

    protected $fillable = [
        'seller_id',
        'product_id',
        'hidden',
    ];
    protected $casts = [
        'hidden' => 'boolean',
    ];

    protected static function newFactory()
    {
        return \Modules\Seller\Database\factories\SellerProductFactory::new();
    }
    public function seller()
    {
        return $this->belongsTo(Seller::class,'seller_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
}
