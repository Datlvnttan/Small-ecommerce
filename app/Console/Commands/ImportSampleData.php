<?php

namespace App\Console\Commands;

use App\Helpers\Helper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportSampleData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:sample';
    // {--module=Modules} {--table=Tables} {--file=File}
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import sample data from a file into the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $tables = $this->argument('Modules');
        // $tables = $this->argument('Tables');
        // $file = $this->argument('File');
        $filePath = database_path('imports/script2.json');
        // $all = false;
        // if (!isset($tables)) {
        //     $all = true;
        // }
        // if (Helper::containsSpecialCharacters($tables, ', ')) {
        //     $this->error('Invalid characters found in table names.');
        //     return 1;
        // }
        // $tablesArr = explode(',', $tables);
        // if (count($tables) == 0) {
        //     $tablesArr = [$tables];
        // }
        // if (!File::exists($filePath)) {
        //     $this->error('File sample data does not exist.');
        //     return 1;
        // }



        $tables = [
            'posts', 'countries', 'shipping_methods',
            'shipping_methods_countries', 'users', 'categories',
            'brands', 'products', 'product_images','flash_sales', 'product_flash_sales',
            'attributes', 'product_attributes', 'product_attribute_options',
            'skus', 'sku_product_attribute_options',
            'discount_coupons', 'orders', 'order_details'
        ];

        $jsonContent = File::get($filePath);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $error = json_last_error_msg();
            $this->error("Decode sample data failed error: " . $error);
            return 1;
        }
        $data = json_decode($jsonContent, true);
        if (!is_array($data)) {
            $this->error('Invalid JSON format in sample data.');
            return 1;
        }
        foreach ($tables as $table) {
            foreach ($data as $obj) {
                if ($obj['type'] == 'table') {
                    $tableName = $obj['name'];
                    if ($table == $tableName) {
                        if (!isset($obj['name']) || !isset($obj['data'])) {
                            $this->error("Invalid sample data structure.");
                            return 1;
                        }
                        $this->info('Importing sample data into table: ' . $tableName);
                        DB::table($obj['name'])->insert($obj['data']);
                        $this->info('Imported sample data into table ' . $tableName . ' successfully');
                        break;
                    }
                }
            }
        }

        return 0;
    }
}
