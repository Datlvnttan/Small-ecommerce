<?php

namespace Modules\Product\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AttributesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Product\Entities\Attribute::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'attribute_name' =>  ucfirst($this->faker->unique()->words(fake()->numberBetween(1,5), true))
        ];
    }
}

