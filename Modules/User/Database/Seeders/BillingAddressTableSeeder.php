<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\BillingAddress;

class BillingAddressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $observer = BillingAddress::getEventDispatcher();
        // BillingAddress::unsetEventDispatcher();
        BillingAddress::factory()->count(300)->create();
        // BillingAddress::setEventDispatcher($observer);
       

        // $this->call("OthersTableSeeder");
    }
}
