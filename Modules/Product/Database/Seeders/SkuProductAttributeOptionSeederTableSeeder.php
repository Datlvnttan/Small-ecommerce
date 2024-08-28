<?php

namespace Modules\Product\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductAttribute;
use Modules\Product\Entities\ProductAttributeOption;
use Modules\Product\Entities\Sku;
use Modules\Product\Entities\SkuProductAttributeOption;

class SkuProductAttributeOptionSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = Product::all();
        foreach ($products as $product) {
            $skuCheck = Sku::where('product_id', $product->id)->first();;
            if (!isset($skuCheck)) {
                $productAttributes = ProductAttribute::where('product_id', $product->id)->get();
                $productAttributeOptionIds = [];
                foreach ($productAttributes as $productAttribute) {
                    $optionIds = ProductAttributeOption::where('product_attribute_id', $productAttribute->id)->pluck('id')->toArray();
                    array_push($productAttributeOptionIds, $optionIds);
                }
                
                $countProductAttributes = count($productAttributeOptionIds);
                if ($countProductAttributes != 0) {
                    $result = [];
                    $this->arraySkuProduct($productAttributeOptionIds, 0, [], $result);
                    $countResult = count($result);
                    $default = fake()->numberBetween(0, $countResult);
                    for ($i = 0; $i < $countResult; $i++) {
                        $productPartNumber = implode("-", $result[$i]);
                        // $skuCheck = Sku::where('product_part_number', $productPartNumber)->first();
                        // if (!isset($skuCheck)) {
                        $skus = Sku::factory()->count(1)->create([
                            'product_id' => $product->id,
                            'default' => ($i == $default),
                            'product_part_number' => $productPartNumber,
                        ]);
                        foreach ($result[$i] as $productAttributeOptionId) {
                            SkuProductAttributeOption::create([
                                'product_attribute_option_id' => $productAttributeOptionId,
                                'sku_id' => $skus[0]->id
                            ]);
                        }
                        // }
                        // else
                        // {
                        //     break;
                        // }
                    }
                } else {
                    Sku::factory()->count(1)->create([
                        'product_id' => $product->id,
                        'default' => true
                    ]);
                }
            }
        }
        // Model::unguard();

        // $this->call("OthersTableSeeder");
    }
    private function arraySkuProduct($arrays, $currentIndex = 0, $currentCombination = [], &$result = [])
    {
        if ($currentIndex == count($arrays)) {
            $result[] = $currentCombination;
            return;
        }
        foreach ($arrays[$currentIndex] as $value) {
            $this->arraySkuProduct($arrays, $currentIndex + 1, array_merge($currentCombination, [$value]), $result);
        }
    }
}
