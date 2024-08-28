<?php

namespace Modules\Product\Repositories\Repository;

use App\Repositories\EloquentRepository;
use Illuminate\Support\Facades\DB;
use Modules\Product\Repositories\Interface\ProductAttributeOptionRepositoryInterface;

class ProductAttributeOptionRepository extends EloquentRepository implements ProductAttributeOptionRepositoryInterface
{
    public function getModel()
    {
        return \Modules\Product\Entities\ProductAttributeOption::class;
    }
    public function getProductAttributeOptionByProductId($productId)
    {
        return   DB::table('product_attributes as pa')
        ->join('attributes as a', 'pa.attribute_id', '=', 'a.id')
        ->leftJoin('product_attribute_options as ao', 'pa.id', '=', 'ao.product_attribute_id')
        ->selectRaw('
            pa.product_id,
            JSON_OBJECT(
                "attribute_id", pa.attribute_id,
                "attribute_name", a.attribute_name,
                "options", JSON_ARRAYAGG(JSON_OBJECT("id", ao.id, "option_name", ao.option_name))
            ) AS attribute_data
        ')
        ->groupBy('pa.product_id', 'pa.attribute_id', 'a.attribute_name')
        ->get();

    }
}
