<?php

namespace Modules\User\Database\Seeders;

use App\Helpers\Helper;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\User;
use Modules\User\Enums\UserMemberType;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('email', '=', 'dat@gmail.com')->first();
        if (!isset($user)) {
            User::create([
                'fullname' => 'Lê Phát Đạt',
                'nickname' => fake()->unique()->regexify('[A-Za-z0-9]{10}'),
                'phone_number' => fake()->unique()->numerify('##########'),
                'email' => 'dat@gmail.com',
                'password' => '123',
                'OTP' => Helper::randomOTPNumeric(),
                'point' => fake()->numberBetween(0, 10000),
                'newsletter_subscription' => fake()->boolean(),
                'point_expiration_notification' => fake()->boolean(),
                'created_at' => fake()->dateTime(),
                'updated_at' => fake()->dateTime(),
                'member_type' => fake()->randomElement(UserMemberType::values()),
                'otp_renew_at' => null,
                'email_verified_at' => now(),
            ]);
        }
        User::factory()->count(10)->create();
        


        // $this->call("OthersTableSeeder");
    }
}
