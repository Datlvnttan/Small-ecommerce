<?php

namespace Modules\Product\Database\factories;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BrandFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Product\Entities\Brand::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->words(fake()->numberBetween(2,4),true);
        return [
            'brand_name'=>ucfirst($name),
            'logo'=>Helper::randomOTP(30).'jpg',
            'total_purchases'=>$this->faker->numberBetween(),
            'total_review'=>$this->faker->numberBetween(),
        ];
    }
}

