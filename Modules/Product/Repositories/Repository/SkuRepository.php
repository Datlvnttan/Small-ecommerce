<?php

namespace Modules\Product\Repositories\Repository;

use App\Repositories\EloquentRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Product\Repositories\BaseProductQueryRepository;
use Modules\Product\Repositories\BaseSkuQueryRepository;
use Modules\Product\Repositories\Interface\SkuRepositoryInterface;

class SkuRepository extends BaseSkuQueryRepository implements SkuRepositoryInterface
{
    public function getModel()
    {
        return \Modules\Product\Entities\Sku::class;
    }
    protected function getQueryModelJoin($model)
    {
        return $model->join('products', "skus.product_id", '=', 'products.id');
    }

    /**
     * Kiểm số lượng tồn kho của sku có đủ 1 lượng nào đó không.
     *
     * @param string $skuId id của sku
     * @param string $quantity số lượng muốn kiểm tra 
     * @return bool True nếu số lượng trong kho còn đủ
     */
    public function checkSkuQuantity($skuId, $quantity = 1)
    {
        $sku = $this->find($skuId);
        if ($sku->quantity < $quantity)
            return false;
        return true;
    }
    public function findSkuDefaultByProductId($productId)
    {
        return $this->model->where('product_id', $productId)->where('default', true)->first();
    }
    /**
     * Summary of findSkuBySkuIdOrSkuDefaulByProductId
     * @param mixed $productId
     * @param mixed $skuId
     * @return Sku
     */
    public function findSkuBySkuIdOrSkuDefaultByProductId($productId, $skuId)
    {
        if (!isset($skuId))
            $sku = $this->findSkuDefaultByProductId($productId);
        else
            $sku = $this->model->where('id', $skuId)->first();
        return $sku;
    }
    public function getByOptions($productId, array $optionIds)
    {
        return $this->model->join('sku_product_attribute_options as so', "{$this->model->table}.id", '=', 'so.sku_id')
            ->where("{$this->model->table}.product_id", $productId)
            ->whereIn('product_attribute_option_id', $optionIds)->get();
    }
    public function getByProductPartNumber($productPartNumber, $memberType = "guest")
    {
        return $this->buildQueryDataProduct($this->baseQuery)
        ->where('product_part_number', 'LIKE', "{$productPartNumber}")->first();
        // return $this->model->where('product_part_number', 'LIKE', "{$productPartNumber}")->first();
    }

    public function getBySkuIds($skuIds, $memberType = "guest")
    {
        return $this->queryDataSkuGroup(function ($query) use ($skuIds) {
            return $query->whereIn("{$this->model->table}.id", $skuIds);
        })->get();

        // $table = $this->model->table;
        // return $this->model->join('products as p', "{$table}.product_id", '=', 'p.id')
        //     ->leftJoin('sku_product_attribute_options as so', "{$table}.id", '=', 'so.sku_id')
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
        //     ->whereIn("{$table}.id", $skuIds)
        //     ->select(
        //         "{$table}.id as sku_id",
        //         "{$table}.product_id",
        //         "{$table}.{$memberType}_price as price_old",
        //         "{$table}.{$memberType}_discount as discount",
        //         'ps.discount as flash_sale_discount',
        //         // DB::raw("{$table}.{$memberType}_price * (1 - {$table}.{$memberType}_discount) as price_new"),
        //         DB::raw("{$table}.{$memberType}_price * (1 - {$table}.{$memberType}_discount) * (1 - COALESCE(ps.discount, 0)) as price_new"),
        //         DB::raw("GROUP_CONCAT(po.option_name ORDER BY po.id SEPARATOR ', ') AS options"),
        //         "{$table}.product_part_number",
        //         'p.product_name',
        //         'p.shipping_point',
        //         "{$table}.quantity as sku_quantity",
        //     )->groupBy(
        //         "{$table}.id",
        //         "{$table}.product_id",
        //         "{$table}.{$memberType}_price",
        //         "{$table}.{$memberType}_discount",
        //         "{$table}.product_part_number",
        //         'p.product_name',
        //         'p.shipping_point',
        //         "{$table}.quantity",
        //         'ps.discount',
        //         'f.start_time',
        //         'f.end_time',
        //     )
        //     ->toSql();
    }
    public function updateInventoryFromOrderItems(int $orderId, bool $add = true)
    {
        $table = $this->model->table;
        $operationSign = $add ? '+' : '-';
        return $this->model->join('order_details AS od', "{$table}.id", '=', 'od.sku_id')
            ->where('od.order_id', $orderId)
            ->update([
                "{$table}.quantity" => DB::raw("{$table}.quantity {$operationSign} od.quantity")
            ]);
    }
}
