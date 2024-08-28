<?php

namespace Modules\Product\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductImage;
use Illuminate\Support\Str;

class ProductImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Product\Entities\ProductImage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $imageName = null;
        $product = Product::inRandomOrder()->first();
        do {
            $name = time().Str::slug($this->faker->words(random_int(2,6),true));
            $imageName = "{$name}.jpg";
        } while (
            ProductImage::where('product_id', $product->id)
            ->where('image_name', $imageName)->exists()
        );

        return [
            'product_id' => $product->id,
            'image_name' => $imageName 
        ];
    }
}
