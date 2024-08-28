<?php

namespace Modules\Cart\Entities;

use App\Models\ModelCompoundPrimaryKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends ModelCompoundPrimaryKey
{
    use HasFactory;
    
    public $table = 'carts';

    protected $primaryKey = ['user_id', 'sku_id'];
    protected $fillable = ['user_id', 'sku_id','quantity'];
    
    protected static function newFactory()
    {
        // return \Modules\Cart\Database\factories\CartFactory::new();
    }
}
