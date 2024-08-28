<?php

namespace Modules\Order\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Order\Database\factories\DiscountCouponFactory;
use Modules\Order\Entities\OrderDetail;

class OrderDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(OrderTableSeeder::class);
        // $this->call(OrderDetailTableSeeder::class);
        // $this->call(DiscountCouponTableSeeder::class);
    }
}
