<?php

namespace Modules\Shipping\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Shipping\Entities\Country;
use Modules\Shipping\Entities\ShippingMethod;
use Modules\Shipping\Entities\ShippingMethodCountry;

class ShippingMethodCountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            // $countShipping = ShippingMethod::count();
            $shippings = ShippingMethod::all();
            $countries = Country::all();
            // $randomQuantity = fake()->numberBetween(2, $countShipping);
            // if(count($shippings)>5)
            // {
            //     return;
            // }
            foreach ($shippings as $shipping) {

                foreach ($countries as $country) {
                    if (ShippingMethodCountry::where('country_id', $country->id)
                        ->where('shipping_method_id', $shipping->id)->exists()
                    ) {
                        break;
                    }
                    ShippingMethodCountry::create([
                        'country_id' => $country->id,
                        'shipping_method_id' => $shipping->id,
                        'expense' => fake()->numberBetween(0, 1000),
                        'discount' => fake()->randomFloat(2, 0, 0.5),
                        'delivery_time' => fake()->numberBetween(0, 30),
                    ]);
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
