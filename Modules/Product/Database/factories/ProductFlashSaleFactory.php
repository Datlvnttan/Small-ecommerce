<?php

namespace Modules\Product\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Product\Entities\FlashSale;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductFlashSale;

class ProductFlashSaleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Product\Entities\ProductFlashSale::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $productId = null;
        $flashSaleId = null;
        $countProduct = Product::count();
        $i = 0;
        do {
            $flashSale = FlashSale::inRandomOrder()->first();
            $product = Product::inRandomOrder()->first();
            $productId = $product->id;
            $flashSaleId = $flashSale->id;
            $i ++;
        } while (ProductFlashSale::where('product_id', $productId)
            ->where('flash_sale_id', $flashSaleId)->exists() || $i == $countProduct
        );

        return [
            'flash_sale_id' => $flashSaleId,
            'product_id' => $productId,
            'discount' => $this->faker->randomFloat(2, 0.03, 0.4)
        ];
    }
}
