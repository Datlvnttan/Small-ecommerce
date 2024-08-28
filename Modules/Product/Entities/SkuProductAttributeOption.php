<?php

namespace Modules\Product\Entities;

use App\Models\ModelCompoundPrimaryKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SkuProductAttributeOption extends ModelCompoundPrimaryKey
{
    use HasFactory;

    public $table = 'sku_product_attribute_options';
    protected $primaryKey = ['sku_id', 'product_attribute_option_id'];

    protected $fillable = ['sku_id', 'product_attribute_option_id'];
    
    protected static function newFactory()
    {
        // return \Modules\Product\Database\factories\SkuProductAttributeOptionFactory::new();
    }
    public function sku()
    {
        return $this->belongsTo(Sku::class,'sku_id');
    }
}
