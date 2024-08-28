<?php

namespace Modules\Product\Entities;

use App\Models\ModelCompoundPrimaryKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductImage extends ModelCompoundPrimaryKey
{
    public const PATH_PRODUCT_IMAGE = 'modules/product/img/tiki/';
    public $table = 'product_images';
    use HasFactory;

    // Đặt khóa chính phức hợp
    protected $primaryKey = ['product_id', 'image_name'];

    // Các trường có thể được gán hàng loạt
    protected $fillable = ['product_id', 'image_name'];  

    protected static function newFactory()
    {
        return \Modules\Product\Database\factories\ProductImageFactory::new();
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
