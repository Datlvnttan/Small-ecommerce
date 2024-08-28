<?php

namespace Modules\User\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Shipping\Entities\Country;
use Modules\User\Entities\Address;
use Modules\User\Entities\DeliveryAddress;
use Modules\User\Entities\User;

class DeliveryAddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\User\Entities\DeliveryAddress::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::inRandomOrder()->first();
        $userId = $user->id;
        $country = Country::inRandomOrder()->first(); //pluck('id')->toArray();
        $countryId = $country->id;
        $addresses = DeliveryAddress::where('user_id', $userId)
            ->where('default', true)->first();
        $default = isset($addresses) ? false : true;
        return [
            'user_id' => $userId,
            'fullname' => $this->faker->firstName() . ' ' . $this->faker->lastName(),
            'country_id' => $countryId,
            'province' => $this->faker->words(fake()->numberBetween(3, 6), true),
            'district' => $this->faker->words(fake()->numberBetween(3, 6), true),
            'ward' => $this->faker->words(fake()->numberBetween(3, 6), true),
            'address_specific' => $this->faker->streetAddress,
            'zip_code' => $this->faker->postcode,
            'default' => $default,
            'phone_number' => $this->faker->numerify('##########'),
            'international_calling_code' => $country->international_calling_code
        ];
    }
}
