<?php

namespace Modules\Order\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Order\Entities\DiscountCoupon;
use Illuminate\Support\Str;

class DiscountCouponTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Model::unguard();
        for ($i = 0; $i < 100; $i++) {
            DiscountCoupon::factory()->create();
        }
       
        $discountCoupon = DiscountCoupon::where('coupon_code','123456')->first();
        if(!isset($discountCoupon))
        {
            DiscountCoupon::create([
                'coupon_code'=> '123456',
                'discount'=> fake()->randomFloat(2,0.01,1),
                'quantity'=> fake()->numberBetween(0,1000)
            ]);
        }
        
        // $this->call("OthersTableSeeder");
    }
}
