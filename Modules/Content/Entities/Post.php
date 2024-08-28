<?php

namespace Modules\Content\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    public $table = 'posts';

    protected $fillable = [
        'image',
        'title',
        'content',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Content\Database\factories\PostFactory::new();
    }
}
