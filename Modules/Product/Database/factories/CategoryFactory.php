<?php

namespace Modules\Product\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Product\Entities\Category;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Product\Entities\Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $data = [
            'category_name' => $this->faker->words(2,true),
        ];
        $random = $this->faker->boolean();
        $category = Category::inRandomOrder()->first();
        if(isset($category) && $random)
        {
            $data['parent_category_id'] = $category->id;
        }
        return $data;
    }
}

