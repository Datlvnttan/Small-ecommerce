<?php

namespace Modules\Product\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Product\Entities\Attribute;
use Modules\Product\Entities\Category;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductAttribute;

class ProductAttributeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Product\Entities\ProductAttribute::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $product_id = null;
        $attribute_id = null;
        $sku = null;
        do {
            $attribute = Attribute::inRandomOrder()->first();
            $product = Product::inRandomOrder()->first();
            $product_id = $product->id;
            $attribute_id = $attribute->id;
            $sku = $product->skus()->first();
        } while (ProductAttribute::where('product_id', $product_id)
            ->where('attribute_id', $attribute_id)->exists() || isset($sku)
        );

        return [
            'attribute_id' => $attribute_id,
            'product_id' => $product_id,
        ];
    }
}
