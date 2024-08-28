<?php

namespace Modules\Product\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\Brand;

class BrandSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Brand::factory()->count(20)->create();
        // Model::unguard();
        // Brand::create([
        //     //'id' => 1
        //     'logo'=>'daytona.jpg',
        //     'brand_name'=>'daytona'
        // ]);
        // Brand::create([
        //     //'id' => 2
        //     'logo'=>'kabuto.jpg',
        //     'brand_name'=>'Kabuto'
        // ]);
        // $this->call("OthersTableSeeder");
    }
}
