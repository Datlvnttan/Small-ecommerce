<?php

namespace Modules\Product\Repositories\Repository;

use App\Repositories\EloquentRepository;
use Closure;
use Illuminate\Support\Facades\DB;
// use Database\Factories\Product;
use Modules\Product\Repositories\Interface\BrandRepositoryInterface;

class BrandRepository extends EloquentRepository implements BrandRepositoryInterface
{
    public function getModel()
    {
        return \Modules\Product\Entities\Brand::class;
    }
    /**
     * Summary of getHotBrands
     * @return array
     */
    public function getHotBrands()
    {
        return $this->model
            ->select(
                "{$this->model->table}.id",
                "{$this->model->table}.logo",
                "{$this->model->table}.brand_name",
                "{$this->model->table}.total_purchases",
                "{$this->model->table}.total_review",
            )
            ->where('total_purchases','>=',config('app.quantityPurchasedCalledHot'))
            ->orderBy('total_purchases','desc')
            ->limit(2)
            ->get(); 

        return $this->model->join('products as p', 'p.brand_id', '=', "{$this->model->table}.id")
            ->join('skus as s', 's.product_id', '=', 'p.id')
            ->join('order_details as od', 's.id', '=', 'od.sku_id')
            ->select(
                "{$this->model->table}.id",
                "{$this->model->table}.logo",
                "{$this->model->table}.brand_name",
                // "{$this->model->table}.total_purchases",
                // "{$this->model->table}.total_review",
                DB::raw('SUM(od.quantity) as total_purchases'),
                DB::raw('COUNT(od.feedback_created_at) as total_review'),
            )
            ->groupBy(
                "{$this->model->table}.id",
                "{$this->model->table}.logo",
                "{$this->model->table}.brand_name"
            )
            ->orderBy('total_purchases','desc')
            ->limit(2)
            ->get();
    }
}
