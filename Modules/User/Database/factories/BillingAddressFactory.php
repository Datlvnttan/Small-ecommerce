<?php

namespace Modules\User\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Shipping\Entities\Country;
use Modules\User\Entities\Address;
use Modules\User\Entities\BillingAddress;
use Modules\User\Entities\User;

class BillingAddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\User\Entities\BillingAddress::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::inRandomOrder()->first();
        $country = Country::inRandomOrder()->first();
        $countryId = $country->id;
        $addresses = BillingAddress::where('user_id', $user->id)
            ->where('default', true)->first();
        $default = isset($addresses) ? false : true;
        $data = [
            'user_id' => $user->id,
            'fullname' => $this->faker->firstName() . ' ' . $this->faker->lastName(),
            'country_id' => $countryId,
            'province' => $this->faker->words(fake()->numberBetween(3, 6), true),
            'district' => $this->faker->words(fake()->numberBetween(3, 6), true),
            'ward' => $this->faker->words(fake()->numberBetween(3, 6), true),
            'address_specific' => $this->faker->streetAddress,
            'zip_code' => $this->faker->postcode,
            'default' => $default,

        ];
        if ($this->faker->boolean) {
            $data['tax_id_number'] = $this->faker->numberBetween(10000, 100000);
        }
        return $data;
    }
}
