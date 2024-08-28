<?php

namespace Modules\Cart\Entities;

use App\Models\ModelCompoundPrimaryKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Favorite extends ModelCompoundPrimaryKey
{
    use HasFactory;

    public $table = 'favorites';

    protected $primaryKey = ['user_id', 'product_id'];
    protected $fillable = ['user_id', 'product_id'];
    
    protected static function newFactory()
    {
        // return \Modules\Cart\Database\factories\WishlistFactory::new();
    }
}
