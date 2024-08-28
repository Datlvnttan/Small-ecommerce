<?php

namespace Modules\Product\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SpecificationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Product\Entities\Specification::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'specification_name'=>$this->faker->unique(true)->words(fake()->numberBetween(1,5)),
            'specification_value'=>$this->faker->unique(true)->words(fake()->numberBetween(1,5)),
        ];
    }
}

