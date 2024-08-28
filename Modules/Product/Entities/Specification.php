<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Specification extends Model
{
    use HasFactory;
    public $table = 'specifications';

    protected $fillable = [
        'specification_name',
        'product_id',
        'specification_value',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Product\Database\factories\SpecificationFactory::new();
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
