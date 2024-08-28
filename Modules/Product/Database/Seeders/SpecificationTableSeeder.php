<?php

namespace Modules\Product\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Modules\Product\Entities\Specification;

class SpecificationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit', '2G');
        $data = [];
        $words = [];
        $wordValues = [];
        for($i=0;$i<100;$i++)
        {
            $word = ucfirst(fake()->unique()->words(fake()->numberBetween(1, 3), true));
            $count = fake()->randomNumber(1, 12);
            for ($j = 0; $j <= $count; $j++) {
                $wordValues[$word][] = ucfirst(fake()->words(fake()->numberBetween(1, 5), true));
            }
            $words[] = $word;
        }
        for ($i = 1; $i <= 139681; $i++) {
            $names = array_unique(fake()->randomElements($words, fake()->randomNumber(1, 10)));
            foreach ($names as $name) {
                $data[] = [
                    'specification_name' => $name,
                    'specification_value' => fake()->randomElement($wordValues[$name]),
                    'product_id' => $i,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                if (count($data) > 1000) {
                    Specification::insert($data);
                    // Log::info("xong ".$i);
                    $data = [];
                }
            }
        }
        Specification::insert($data);
    }
}
