<?php

namespace Modules\Product\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Product\Entities\ProductAttribute;
use Modules\Product\Entities\ProductAttributeOption;
use Modules\Product\Entities\Sku;

class ProductAttributeOptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Product\Entities\ProductAttributeOption::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // $optionName = '';
        // $productAttributeId = null;
        // $sku = null;
        // do {
        //     $productAttribute = ProductAttribute::inRandomOrder()->first();
        //     $optionName = $this->faker->words(random_int(2,5), true);
        //     $productAttributeId = $productAttribute->id;
        //     $sku = Sku::where('product_id',$productAttribute->product_id)->first();
        // } while (ProductAttributeOption::where('product_attribute_id', $productAttributeId)
        //     ->where('option_name', $optionName)->exists() || isset($sku)
        // );

        // // $attributeIds = Attribute::pluck('id')->toArray();
        
        // return [
        //     'product_attribute_id'=>$productAttributeId,
        //     'option_name'=>$optionName,
        // ];
    }
}

