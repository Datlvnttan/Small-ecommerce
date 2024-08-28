<?php

namespace Modules\Product\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductFlashSale;

class ProductFlashSaleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (ProductFlashSale::count() == 0)
            ProductFlashSale::factory()->count(50)->create();

        // $this->call("OthersTableSeeder");
    }
}
