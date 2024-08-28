<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    use \Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;
    public $table = 'categories';

    protected $fillable = [
        'category_name',
        'parent_category_id'
    ];

    public function getParentKeyName()
    {
        return 'parent_category_id';
    }
    public function getLocalKeyName()
    {
        return 'id';
    }
    protected static function newFactory()
    {
        return \Modules\Product\Database\factories\CategoryFactory::new();
    }
    public function subCategories()
    {
        return $this->hasMany(Category::class, 'parent_category_id');
    }
    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_category_id');
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
    public function getDescendants()
    {
        $fullProducts = $this->products;
        if (count($fullProducts) == 0)
            return [];
        $subCategories = $this->subCategories;
        foreach ($subCategories as $subcategory) {
            $fullProducts = $fullProducts->union($subcategory->getDescendants());
        }
        return $fullProducts;
    }
    // public function getCustomPaths()
    // {
    //     return [
    //         [
    //             'name' => 'slug_path',
    //             'column' => 'slug',
    //             'separator' => '/',
    //         ],
    //     ];
    // }
    public function getSlugAttribute()
    {
        return \Illuminate\Support\Str::slug($this->category_name);
    }
    // public function getPathAttribute()
    // {
    //     return $this->path;
    // }
    protected $appends = ['slug'];
    public function recursiveProducts($callback = null)
    {
        $query = $this->hasManyOfDescendantsAndSelf(Product::class);
        if(isset($callback))
        {
            return $callback($query);
        }
        return $query;
    }
}
