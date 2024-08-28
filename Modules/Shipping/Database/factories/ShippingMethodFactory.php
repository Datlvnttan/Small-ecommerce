<?php

namespace Modules\Shipping\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Shipping\Entities\ShippingMethod;

class ShippingMethodFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Shipping\Entities\ShippingMethod::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $data = [
            'shipping_method_name'=>$this->faker->words(4,true),
        ];
        // $shippingMethodCount = ShippingMethod::count();
        return $data;
    }
}
