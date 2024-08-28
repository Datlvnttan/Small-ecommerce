<?php

namespace App\Console\Commands;

use App\Imports\RawDataImport;
use App\Services\WebScraperService;
use Attribute;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Product\Entities\Brand;
use Modules\Product\Entities\FlashSale;
use Modules\Product\Entities\Product;
use Modules\Seller\Entities\Seller;
use Modules\Seller\Entities\SellerProduct;
use Modules\User\Entities\User;
use PhpOffice\PhpSpreadsheet\IOFactory;

class data extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dataaa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $this->importsDataTikiFileTxtAll();

        // if (FlashSale::count() == 0) {
        //     FlashSale::factory()->count(1)->create();
        // }
        // $scraper = new WebScraperService();

        // // old:23093327
        // //22083050

        // $countId = Product::count();

        // for ($i = 23658350; $i <= 26058059; $i += 10) {
        //     $scraper->scraperProductDetail($i);
        //     $this->info("Product $i processed");
        // }
    }
    protected function importsDataTikiFileTxtAll()
    {
        $filePath = database_path('imports/tiki.txt');
        $contents = file_get_contents($filePath);
        $lines = explode("\n", $contents);
        $scraper = new WebScraperService();
        $flashSale = FlashSale::first();
        $brandIds = Brand::pluck('id')->toArray();
        // $name = 'Combo Rèn Kĩ Năng Học Tốt Toán 8Tự Học - Nâng Cao Kiến Thức Toán 8';
        // $product = Product::where('product_name', $name)->first();
        // $id = $product->id;
        // $count = count($lines);
        $check = true;
        $this->info('Processing...');
        foreach ($lines as $line) {
            // for ($i = $id; $i < $count; $i++) {
            // $line = $lines[$i];
            $data = explode('","', $line);
            // $this->info('Processing url ' . $data[1]);
            $brandId = fake()->randomElement($brandIds);
            $check = $scraper->buildData($data, $flashSale->id, $brandId, $check);
        }
        //Lưu thuộc tính


        // $userIds = User::pluck('id')->toArray();
        // $number = 1;
        // $scraper = new WebScraperService();
        // foreach ($lines as $line) {
        //     $data = explode('","', $line);
        //     $userId = fake()->randomElement($userIds);
        //     try {

        //         $scraper->getSpecifications($data[2], $userId, $number);
        //     } catch (\Throwable $th) {
        //         $this->warn('Error processing ' . $data[1]);
        //         $seller = null;
        //         $product = Product::where('product_name', $data[1])->first();
        //         do {
        //             $seller = Seller::inRandomOrder()->first();
        //         } while (SellerProduct::where('seller_id', $seller->id)->where('product_id', $product->id)->exists());
        //         SellerProduct::create([
        //             'product_id' => $product->id,
        //             'seller_id' => $seller->id,
        //             'hidden' => fake()->boolean(3),
        //         ]);
        //     }
        // }
        // $this->info("Product processed");
    }
    protected function importsDataTiki($url, $flashSaleId)
    {
        $scraper = new WebScraperService();
        $scraper->scraperProductTikiDetails($url, $flashSaleId);
    }
    // protected function readFileTxt()
    // {
    //     $filePathTxt = database_path('imports/tiki.txt');
    //     $content = file_get_contents($filePathTxt);
    //     $lines = explode("\n", $content);
    //     $flashSale = FlashSale::first();
    //     //đã tới 'Combo Rèn Kĩ Năng Học Tốt Toán 8Tự Học - Nâng Cao Kiến Thức Toán 8'


    //     //thao tác lưu product
    //     // foreach ($lines as $line) {
    //     //     // ;
    //     //     $this->info('Processing url ' . $line);
    //     //     $this->importsDataTiki($line, $flashSale->id);
    //     //     $this->info("Product $line processed");
    //     // }


    // }
    protected function readExcel()
    {

        ini_set('memory_limit', '1G');
        $filePath = database_path('imports/tiki.csv');
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $this->info('đọc');
        // Chuyển đổi chỉ mục cột từ ký tự (như 'A') sang chỉ số số
        $columnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString('C');

        // Lấy giá trị của cột "url" cho từng hàng
        $urls = '';
        foreach ($worksheet->getRowIterator(2) as $row) { // Bắt đầu từ hàng thứ 2 để bỏ qua tiêu đề
            $cell = $worksheet->getCellByColumnAndRow($columnIndex, $row->getRowIndex());
            $urls .= $cell->getValue() . PHP_EOL;
            // Log::info($cell->getValue());
        }
        $filePathTxt = database_path('imports/tiki.txt');
        file_put_contents($filePathTxt, $urls);
    }
}
