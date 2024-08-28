<?php

namespace Modules\Cart\Repositories\Repository;

use App\Repositories\EloquentCompoundPrimaryKeyRepository;
use App\Repositories\EloquentRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Cart\Repositories\Interface\CartRepositoryInterface;
use Modules\Product\Repositories\BaseSkuQueryRepository;

class CartRepository extends BaseSkuQueryRepository implements CartRepositoryInterface
{
    public function getModel()
    {
        return \Modules\Cart\Entities\Cart::class;
    }

    public function find($ids, $columns = ['*'])
    {
        $query = $this->model;
        foreach ($ids as $key => $value) {
            $query = $query->where($key, $value);
        }
        return $query->first($columns);
    }

    protected function getQueryModelJoin($model)
    {
        return $model->join('skus', "{$this->model->table}.sku_id", '=', 'skus.id')
            ->join('products', "skus.product_id", '=', 'products.id');
    }
    public function getCartByUserId($userId)
    {
        return $this->queryDataSkuGroup(function ($query) use ($userId) {
            return $query->where("{$this->model->table}.user_id", $userId);
        }, [
            "{$this->model->table}.quantity as cart_quantity"
        ])->get();
        // return $this->model
        //     ->join('skus as s', "{$this->model->table}.sku_id", '=', 's.id')
        //     ->join('products as p', 's.product_id', '=', 'p.id')
        //     ->leftJoin('sku_product_attribute_options as so', "{$this->model->table}.sku_id", '=', 'so.sku_id')
        //     ->leftJoin('product_attribute_options as po', 'so.product_attribute_option_id', '=', 'po.id')
        //     ->leftJoin('product_flash_sales as ps', 'p.id', '=', 'ps.product_id')
        //     ->leftJoin('flash_sales as f', function ($join) {
        //         $join->on('ps.flash_sale_id', '=', 'f.id')
        //             ->where(function ($query) {
        //                 $query->where(function ($query) {
        //                     $query->where('f.start_time', '<=', DB::raw('NOW()'))
        //                         ->where('f.end_time', '>', DB::raw('NOW()'));
        //                 })
        //                     ->orWhereNull('f.start_time');
        //             });
        //     })
        //     ->where("{$this->model->table}.user_id", $userId)
        //     ->select(
        //         's.id as sku_id',
        //         's.product_id',
        //         'p.product_name',
        //         'p.shipping_point',
        //         "{$this->model->table}.sku_id",
        //         "s.{$memberType}_price as price_old",
        //         "s.{$memberType}_discount as discount",
        //         'ps.discount as flash_sale_discount',
        //         DB::raw("s.{$memberType}_price * (1 - s.{$memberType}_discount) * (1 - COALESCE(ps.discount, 0)) as price_new"),
        //         DB::raw("GROUP_CONCAT(po.option_name ORDER BY po.id SEPARATOR ', ') AS options"),
        //         's.quantity as sku_quantity',
        //         "{$this->model->table}.quantity as cart_quantity",
        //         's.product_part_number',
        //     )->groupBy(
        //         's.id',
        //         's.product_id',
        //         'p.product_name',
        //         'p.shipping_point',
        //         "{$this->model->table}.sku_id",
        //         "s.{$memberType}_price",
        //         "s.{$memberType}_discount",
        //         's.quantity',
        //         's.product_part_number',
        //         "{$this->model->table}.quantity",
        //     )
        //     ->get();
    }

    public function addProductToCart($userId, $skuId, $quantity = 1)
    {
        $cartItem = $this->model->where([
            'user_id' => $userId,
            "sku_id" => $skuId,
        ])->first();
        if (isset($cartItem)) {
            $cartItem->quantity += $quantity;
            // return 1;
            $cartItem->save();
        } else {
            $cartItem = $this->model->create([
                "user_id" => $userId,
                "sku_id" => $skuId,
                "quantity" => $quantity,
            ]);
        }
    }
    public function removeItemsBykuIds($skuIds, $userId)
    {
        return $this->model->whereIn('sku_id', $skuIds)->where('user_id', $userId)->delete();
    }
}
