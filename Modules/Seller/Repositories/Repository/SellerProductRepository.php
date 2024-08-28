<?php

namespace Modules\Seller\Repositories\Repository;

use App\Repositories\EloquentRepository;
use Modules\Product\Repositories\BaseProductQueryRepository;
use Modules\Seller\Repositories\Interface\SellerProductRepositoryInterface;

class SellerProductRepository extends BaseProductQueryRepository implements SellerProductRepositoryInterface
{
    public function getModel()
    {
        return \Modules\Seller\Entities\SellerProduct::class;
    }
    protected function getQueryModelJoin($model)
    {
        return $model->join('products', function ($join) {
            $join->on('seller_products.product_id', '=', 'products.id');
        })->join('skus', 'products.id', '=', 'skus.product_id');
    }
    public function getAllProductsBySellerId(int $sellerId)
    {
        return $this->queryDataProduct(null, [
            "{$this->model->table}.hidden"
        ])->where("{$this->model->table}.seller_id", $sellerId)->get();
    }
}
