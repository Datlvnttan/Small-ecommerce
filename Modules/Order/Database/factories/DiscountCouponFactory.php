<?php

namespace Modules\Order\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Order\Entities\DiscountCoupon;

class DiscountCouponFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Order\Entities\DiscountCoupon::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $code = DiscountCoupon::count()+1;
        while(DiscountCoupon::where('coupon_code', $code)->first()){
            $code ++;
        }
        $code = str($code);
        return [
            'coupon_code'=> $code,
            'discount'=> fake()->randomFloat(2,0.01,1),
            'quantity'=> fake()->numberBetween(0,100)
        ];
    }
}

