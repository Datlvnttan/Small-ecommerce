<?php

namespace Modules\Product\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\ProductAttribute;
use Modules\Product\Entities\ProductAttributeOption;
use Modules\Product\Entities\Sku;

class ProductAttributeOptionSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ProductAttributeOption::factory()->count(2000)->create();
        $productAttributes = ProductAttribute::all();
        
        foreach ($productAttributes as $productAttribute) {
            $sku = Sku::where('product_id',$productAttribute->product_id)->first();
            if(!isset($sku))
            {
                $ran = null;
                $ranboolean = fake()->boolean(90);
                if($ranboolean)
                {
                    $ran = fake()->numberBetween(1,5);
                }
                else
                {
                    $ran = fake()->numberBetween(6,8);
                }
                
                $productAttributeId = $productAttribute->id;
                for ($i=0; $i < $ran; $i++) { 
                    do {
                        $optionName = fake()->unique()->words(random_int(2,5), true);
                        
                    } while (ProductAttributeOption::where('product_attribute_id', $productAttributeId)
                        ->where('option_name', $optionName)->exists()
                    );
                    ProductAttributeOption::create([
                        'product_attribute_id' => $productAttributeId,
                        'option_name' => $optionName,
                    ]);
                }
            }
        }
        
    }
}
