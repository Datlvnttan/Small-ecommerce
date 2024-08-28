<?php

namespace Modules\Product\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\Sku;
use Modules\Product\Entities\SkuProductAttributeOption;

class SkuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $skus = Sku::all();
        // foreach ($skus as $sku) {
        //     $skuProductAttributeOptions = SkuProductAttributeOption::where('sku_id', $sku->id)->orderBy('product_attribute_option_id')->get();
        //     if (count($skuProductAttributeOptions) > 0) {
        //         $arrOptions = $skuProductAttributeOptions->pluck('product_attribute_option_id')->toArray();
        //         $productPartNumber = implode("-", $arrOptions);
        //         // $productPartNumber = $sku->product_id . '-' . $productPartNumber;
        //         $sku->update(['product_part_number' => $productPartNumber]);
        //     } else
        //         $sku->update(['product_part_number' => null]);
        // }
    }
}
