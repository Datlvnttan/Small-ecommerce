<?php

namespace Modules\Product\Repositories;

use App\Helpers\Helper;
use App\Repositories\EloquentRepository;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

abstract class BaseProductQueryRepository extends EloquentRepository
{
    protected $baseQuery;
    protected $memberType;
    public function __construct()
    {
        parent::__construct();
        $this->memberType = Helper::getMemberType();
        $this->baseQuery = $this->getQueryModelJoin($this->model);
    }
    abstract protected function getQueryModelJoin($model);
    protected function getSelectDataProduct()
    {
        return [
            "products.product_name",
            "products.cover_image",
            "products.shipping_point",
            "products.category_id",
            "products.brand_id",
            "products.average_rating",
            "products.total_rating",
            "products.created_at",
            "products.total_quantity_sold",
        ];
    }
    protected function queryDataProduct(Closure $buildQuery = null, array $select = null)
    {
        $selectDefault = $this->getSelectDataProduct();
        if (isset($select))
        {
            $selectDefault = array_merge($selectDefault, $select);
        }
        return $this->buildQueryDataProduct($this->baseQuery, $buildQuery, $selectDefault);
    }
    protected function buildQueryDataProduct($baseQuery,Closure $buildQuery = null, array $select = null)
    {
        $selectDefault = [
            "products.id",
            "skus.id as sku_id",
            "skus.product_part_number",
            "skus.quantity as sku_quantity",
            "skus.{$this->memberType}_price as price",
            // DB::raw("LEAST( (IFNULL(product_flash_sales.discount, 0) + IFNULL(skus.{$this->memberType}_discount,0)),1) as discount"),
            "skus.{$this->memberType}_discount as product_discount",
            'product_flash_sales.discount as product_flash_sale_discount',
            'flash_sales.start_time as product_flash_sale_start_time',
            'flash_sales.end_time as product_flash_sale_end_time'
        ];
        if (isset($select))
            $selectDefault = array_merge($selectDefault, $select);
        $query =  $baseQuery
            ->leftJoin('product_flash_sales', 'products.id', '=', 'product_flash_sales.product_id')
            ->leftJoin('flash_sales', function ($join) {
                $join->on('product_flash_sales.flash_sale_id', '=', 'flash_sales.id')
                ->whereBetween(DB::raw('NOW()'), [DB::raw('flash_sales.start_time'), DB::raw('flash_sales.end_time')]);
            })
            ->select($selectDefault);
        if (isset($buildQuery))
            $query = $buildQuery($query);
        return $query;
    }   
}
