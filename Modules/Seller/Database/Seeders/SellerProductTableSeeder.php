<?php

namespace Modules\Seller\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Seller\Entities\Seller;
use Modules\Seller\Entities\SellerProduct;

class SellerProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        $sellerIds = Seller::pluck('id')->toArray();
        for ($i = 1; $i <= 139681; $i++) {
            $count = fake()->randomNumber(1, 7);
            $sellerRanIds =  array_unique(fake()->randomElements($sellerIds, $count));
            foreach ($sellerRanIds as $sellerRanId) {
                $data[] = [
                    'seller_id' => $sellerRanId,
                    'product_id' => $i,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            if (count($data) > 500) {
                SellerProduct::insert($data);
                $data = [];
            }
        }
    }
}
