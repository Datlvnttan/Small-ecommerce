<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Shipping\Entities\Country;
use Modules\User\Entities\DeliveryAddress;

class DeliveryAddressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $observer = DeliveryAddress::getEventDispatcher();
        // DeliveryAddress::unsetEventDispatcher();
        DeliveryAddress::factory()->count(300)->create();
        // DeliveryAddress::setEventDispatcher($observer);

        // $deliveryAddresses = DeliveryAddress::all();
        // foreach ($deliveryAddresses as $deliveryAddress) {
        //     if($deliveryAddress->international_calling_code == null)
        //     {
        //         $countryIICallingCodes = Country::pluck('international_calling_code')->toArray();
        //         $deliveryAddress->international_calling_code = fake()->randomElement($countryIICallingCodes);
        //         $deliveryAddress->save();
        //     }
        // }
    }
}
