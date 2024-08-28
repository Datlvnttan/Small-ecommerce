<?php

namespace Modules\Product\Repositories\Repository;

use App\Repositories\EloquentRepository;
use Illuminate\Support\Facades\DB;
use Modules\Product\Entities\Category;
use Modules\Product\Entities\Product;
use Modules\Product\Repositories\Interface\CategoryRepositoryInterface;
// use Database\Factories\Product;

class CategoryRepository extends EloquentRepository implements CategoryRepositoryInterface
{
    public function getModel()
    {
        return \Modules\Product\Entities\Category::class;
    }
    public function getHotCategories($quantity)
    {
        $categories = $this->model->join('products as p', 'categories.id', '=', 'p.category_id')
            ->join('skus as s', 's.product_id', '=', 'p.id')
            ->join('order_details as od', 's.id', '=', 'od.sku_id')
            ->select('categories.id', 'categories.category_name', DB::raw('SUM(od.quantity) as total_quantity'))
            ->groupBy('categories.id', 'categories.category_name')
            ->having('total_quantity', '>=', $quantity)
            ->get();
        return $categories;
    }
    public function showCategoryHierarchy($categoryId)
    {
        $query = <<<SQL
                WITH RECURSIVE CategoryHierarchy AS (
                    SELECT categories.id,categories.category_name,categories.parent_category_id
                    FROM categories
                    WHERE id = ?  

                    UNION ALL

                    SELECT c.id,c.category_name,c.parent_category_id
                    FROM categories c
                    INNER JOIN CategoryHierarchy ch ON c.id  = ch.parent_category_id
                )
                SELECT id,category_name,parent_category_id FROM CategoryHierarchy ORDER BY parent_category_id
                SQL;

        return DB::select($query, [$categoryId]);
    }
    public function getRootCategories()
    {
        return $this->model->whereNull('parent_category_id')->get();
    }
    public function getSubcategories(int $parentCategoryId)
    {
        return $this->model->where('parent_category_id', $parentCategoryId)->get();
    }
    
    // public function getFullProductById(int $id)
    // {
    //     return $this->model->where('id')
    // }
    // protected function getBranchSubcategories($query, $id)
    // {
    //     return $query->with(['subCategories'=>function($subQuery){
    //         $subQuery->where('parent_category_id', $id);
    //     }]);
    // }

    public function getSubcategoriesAndRecursiveProducts(int $categoryId = null)
    {
        return $this->model->where('parent_category_id', $categoryId)->with(['subcategories', 'recursiveProducts' => function ($query) {
            $query->paginate(5);
        }])->get();
    }
    public function getDescendants(int $categoryId)
    {
        $category = $this->model->find($categoryId);
        if (isset($category)) {
            return $category->children;
        }
        return null;
    }
    public function getDescendantsAndSelf(int $parentCategoryId)
    {
        $category = $this->model->find($parentCategoryId);
        if(isset($category))
        {
            return $category->descendantsAndSelf();
        }
        return null;
    }
}
