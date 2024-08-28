<?php

namespace Modules\Shipping\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Shipping\Entities\ShippingMethod;

class ShippingMethodTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = ShippingMethod::count();
        if ($count < 5) {
            ShippingMethod::factory()->count(5 - $count)->create();
            $shippingMethod = ShippingMethod::where('default', true)->first();
            if (!isset($shippingMethod)) {
                ShippingMethod::inRandomOrder()->first()->update(
                    [
                        'default' => true,
                    ]
                );
            }
        }

        // $this->call("OthersTableSeeder");
    }
}
