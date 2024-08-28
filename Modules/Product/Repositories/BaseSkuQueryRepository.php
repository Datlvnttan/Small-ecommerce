<?php

namespace Modules\Product\Repositories;

use App\Repositories\EloquentRepository;
use Closure;
use Illuminate\Support\Facades\DB;

abstract class BaseSkuQueryRepository extends BaseProductQueryRepository
{
    protected $baseSkuQuery;
    public function __construct()
    {
        parent::__construct();
        $this->baseSkuQuery = $this->baseQuery
            ->leftJoin('sku_product_attribute_options', "skus.id", '=', 'sku_product_attribute_options.sku_id')
            ->leftJoin('product_attribute_options', 'sku_product_attribute_options.product_attribute_option_id', '=', 'product_attribute_options.id');
    }
    protected function queryDataSkuGroup(Closure $buildQuery = null, array $select = null)
    {
        $selectGroup = [
            DB::raw("GROUP_CONCAT(product_attribute_options.option_name ORDER BY product_attribute_options.id SEPARATOR ', ') AS options")
        ];
        $selectGroup = array_merge($selectGroup, $this->getSelectDataProduct());
        if (isset($select)) {
            $selectGroup = array_merge($selectGroup, $select);
        }
        return $this->buildQueryDataProduct($this->baseSkuQuery,function ($query) use ($buildQuery) {
            $query = $query->groupBy("skus.id");
            if (isset($buildQuery))
                $query = $buildQuery($query);
            return $query;
        },  $selectGroup);
    }
}
