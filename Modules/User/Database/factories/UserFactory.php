<?php

namespace Modules\User\Database\factories;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\Enums\UserMemberType;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\User\Entities\User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $data = [
            'fullname' => $this->faker->name,
            'nickname' => $this->faker->unique()->regexify('[A-Za-z0-9]{10}'),
            'phone_number' => $this->faker->unique()->numerify('##########'),
            'email' => $this->faker->unique()->email(),
            'password' => '123',
            'OTP' => Helper::randomOTPNumeric(),
            'birthday' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['male', 'female', null]),
            'point' => $this->faker->numberBetween(0, 10000),
            'newsletter_subscription' => $this->faker->boolean(),
            'point_expiration_notification' => $this->faker->boolean(),
            'provider_id' => null,
            'remember_token' => $this->faker->sha256(),
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->dateTime(),
            'member_type' => $this->faker->randomElement(UserMemberType::values()),
            'otp_renew_at' => null,
        ];
        // $random = $this->faker->numberBetween(0, 1);
        if ($this->faker->boolean(70))
            $data['email_verified_at'] = $this->faker->dateTime();
        return $data;
    }
}
