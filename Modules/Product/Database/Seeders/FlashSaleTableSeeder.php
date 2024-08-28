<?php

namespace Modules\Product\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\FlashSale;

class FlashSaleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(FlashSale::count() == 0)
        {
            FlashSale::factory()->count(1)->create();
        }
    }
}
