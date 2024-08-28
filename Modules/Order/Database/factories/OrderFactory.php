<?php

namespace Modules\Order\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Order\Entities\DiscountCoupon;
use Modules\Order\Enums\OrderStatus;
use Modules\Shipping\Entities\Country;
use Modules\Shipping\Entities\ShippingMethod;
use Modules\User\Entities\DeliveryAddress;
use Modules\User\Entities\User;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Order\Entities\Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $userId = null;
        $user = User::inRandomOrder()->first();
        if (fake()->boolean(70)) {
            $userId = $user->id;
        }
        $country = Country::inRandomOrder()->first();
        $deliveryAddressJson = [
            'fullname' => $this->faker->lastName() . ' ' . $this->faker->firstName(),
            'country' => $country->country_name,
            'province' => $this->faker->words(fake()->numberBetween(2, 5), true),
            'district' => $this->faker->words(fake()->numberBetween(2, 5), true),
            'ward' => $this->faker->words(fake()->numberBetween(2, 5), true),
            'address_specific' => $this->faker->streetAddress,
            'zip_code' => $this->faker->postcode,
            'phone_number' => $this->faker->numerify('##########'),
            'international_calling_code' => $country->international_calling_code
        ];

        $billingAddressJson = [
            'fullname' => $this->faker->lastName() . ' ' . $this->faker->firstName(),
            'country' => $country->country_name,
            'province' => $this->faker->words(fake()->numberBetween(2, 5), true),
            'district' => $this->faker->words(fake()->numberBetween(2, 5), true),
            'ward' => $this->faker->words(fake()->numberBetween(2, 5), true),
            'address_specific' => $this->faker->streetAddress,
            'zip_code' => $this->faker->postcode,
        ];
        
        if ($this->faker->boolean) {
            $billingAddressJson['tax_id_number'] = $this->faker->numberBetween(10000, 100000);
        }
        $shippingMethod = ShippingMethod::inRandomOrder()->first();
        $shippingMethodJson = [
            'shipping_method_name' => $shippingMethod->shipping_method_name,
            'expense' => $shippingMethod->expense,
            'discount' => $shippingMethod->discount,
            'delivery_time' => $shippingMethod->delivery_time
        ];
        
        $data = [
            'payment_method' => $this->faker->words(2,true),
            'user_id' => $userId,
            'delivery_address' => $deliveryAddressJson,
            'shipping_method' => $shippingMethodJson,
            'billing_address'=>$billingAddressJson,
            'total_point' => $this->faker->numberBetween(100, 30000),
            'order_key'=>$this->faker->dateTime()->getTimestamp(),
            'email'=>'datlvnttan@gmail.com',
            'is_paid'=>$this->faker->boolean(),
            'note'=> $this->faker->boolean ? $this->faker->sentence(fake()->numberBetween(5,12),true) : null,
            'status' => $this->faker->randomElement(OrderStatus::getOrderedValues()),
            'total_amount' => 0,
        ];
        if(fake()->boolean())
        {
            $discountCouponJson = [
                'coupon_code'=> Str::random(7),
                'discount'=> fake()->randomFloat(2,0.01,1),
            ];
            $data['discount_coupon'] = $discountCouponJson;
        }
        return $data;
    }
}
