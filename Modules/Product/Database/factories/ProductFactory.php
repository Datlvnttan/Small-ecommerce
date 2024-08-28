<?php

namespace Modules\Product\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Product\Entities\Brand;
use Modules\Product\Entities\Category;
use Illuminate\Support\Str;


class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Product\Entities\Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $category = Category::inRandomOrder()->first();
        $brand = Brand::inRandomOrder()->first();
        $product_name = $this->faker->words(random_int(2,6), true);
        $averageRating = fake()->randomFloat(2,0,5);
        if($averageRating > 0)
            $totalRating = fake()->numberBetween(0,1000);
        else
            $totalRating = 0;
        return [
            'product_name' => $product_name,
            'cover_image'=>Str::slug($product_name).'.jpg',
            'describe' => $this->faker->sentence(fake()->numberBetween(7,20)),
            'detail'=> $this->faker->text(fake()->numberBetween(100,400)),
            'shipping_point'=> fake()->numberBetween(7,200),
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'average_rating' => $averageRating,
            'total_rating' => $totalRating,
            'total_quantity_sold' => fake()->numberBetween(0,100000),     
            'created_at' => fake()->dateTimeBetween('-40 days')
        ];
    }
}

