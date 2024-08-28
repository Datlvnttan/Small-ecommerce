<?php

namespace Modules\Product\Repositories\Repository;

use App\Repositories\EloquentRepository;
use Illuminate\Support\Facades\DB;
// use Modules\Product\Entities\ProductFlashSale;
use Modules\Product\Repositories\BaseProductQueryRepository;
use Modules\Product\Repositories\Interface\ProductFlashSaleRepositoryInterface;
use Modules\Product\Services\ProductFlashSaleService;

class ProductFlashSaleRepository extends BaseProductQueryRepository implements ProductFlashSaleRepositoryInterface
{
    public function getModel()
    {
        return \Modules\Product\Entities\ProductFlashSale::class;
    }
    protected function getQueryModelJoin($model)
    {
        return $model->join('products', "{$model->table}.product_id", '=', 'products.id')
            ->join('skus', 'products.id', '=', 'skus.product_id');
    }
    // public function getProductFlashSaleByFlashSaleId($flashSaleId, $isPaginate = false)
    // {
    //     $query = $this->queryDataProduct()->where('flash_sale_id', $flashSaleId)
    //         ->where('skus.default', true);

    //     // $query = $this->model->join('products', "{$this->model->table}.product_id", '=', 'products.id')
    //     //     ->join('skus', 'products.id', '=', 'skus.product_id')
    //     //     // ->join('flash_sales', 'flash_sales.id', '=', "{$this->model->table}.flash_sale_id")
    //     //     ->select(
    //     //         "flash_sale_id",
    //     //         'product_name',
    //     //         // 'cover_image',
    //     //         'shipping_point',
    //     //         'category_id',
    //     //         'brand_id',
    //     //         'products.id',
    //     //         "{$type}_price as price",
    //     //         DB::raw("LEAST({$type}_discount + {$this->model->table}.discount,1) as discount"),
    //     //         'product_part_number',
    //     //         'products.average_rating',
    //     //         'products.total_rating',
    //     //         'products.created_at',
    //     //         'products.total_quantity_sold',
    //     //         'skus.id as sku_id'
    //     //     )
    //     //     ->where('flash_sale_id', $flashSaleId)
    //     //     ->where('skus.default', true);
    //     if ($isPaginate) {
    //         return $query->paginate(ProductFlashSaleService::PER_PAGE_FLASH_SALE);
    //     } else {
    //         return $query->get();
    //     }
    // }
}
