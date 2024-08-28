<?php

namespace Modules\Seller\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\User\Entities\User;

class SellerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userIds = User::pluck('id')->toArray();
        $data = [];
        for ($i = 1; $i <= 1018; $i++) {
            $data[] = [
                'seller_name' => ucfirst(fake()->words(fake()->numberBetween(1, 5),true)) . '_' . $i,
                'email' => 'seller' . $i . '@gmail.com',
                'logo' => 'logo' . $i . '.jpg',
                'user_id' => fake()->randomElement($userIds),
                'locked' => fake()->boolean(5),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if ($i == 518) {
                DB::table('sellers')->insert($data);
                $data = [];
            }
        }
        DB::table('sellers')->insert($data);
    }
}
