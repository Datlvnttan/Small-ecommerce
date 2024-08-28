<?php

namespace Modules\Product\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\Attribute;

class AttributeSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Attribute::factory()->count(100)->create();
        // Model::unguard();
        // Attribute::create([
        //     'attribute_name' => 'Size',
        // ]);
        // Attribute::create([
        //     'attribute_name' => 'Color',
        // ]);
        // // $this->call("OthersTableSeeder");
    }
}
