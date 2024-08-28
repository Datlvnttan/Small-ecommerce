<?php

namespace Modules\Product\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Product\Entities\Product;

class SkuFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Product\Entities\Sku::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $guestPrice = $this->faker->numberBetween(10, 1000);
        $guestDiscount = 0;
        if (fake()->boolean()) {
            $guestDiscount = $this->faker->randomFloat(2, 0, 0.5);
        }
        $memberRetailPrice = $guestPrice - $this->faker->numberBetween(0, $guestPrice);
        if (fake()->boolean()) {
            $memberRetailDiscount = $guestDiscount - $this->faker->randomFloat(2, 0, $guestDiscount);
        } else {
            $memberRetailDiscount = $guestDiscount;
        }
        $memberWholesalePrice = $memberRetailPrice - $this->faker->numberBetween(0, $memberRetailPrice);
        if (fake()->boolean()) {
            $memberWholesaleDiscount = $memberRetailDiscount - $this->faker->randomFloat(2, 0, $memberRetailDiscount);
        } else {
            $memberWholesaleDiscount = $memberRetailDiscount;
        }

        // $productIds = Product::pluck('id')->toArray();
        return [
            'guest_price' => $guestPrice,
            'guest_discount' => $guestDiscount,
            'member_retail_price' => $memberRetailPrice,
            'member_retail_discount' => $memberRetailDiscount,
            'member_wholesale_price' => $memberWholesalePrice,
            'member_wholesale_discount' => $memberWholesaleDiscount,
            'quantity' => $this->faker->numberBetween(0, 100000),
        ];
    }
}
