<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Database\Factories\Product;
use Illuminate\Database\Seeder;
use Modules\Content\Database\Seeders\PostTableSeeder;
use Modules\Order\Database\Seeders\DiscountCouponTableSeeder;
use Modules\Order\Database\Seeders\OrderDetailTableSeeder;
use Modules\Order\Database\Seeders\OrderTableSeeder;
use Modules\Product\Database\Seeders\AttributeSeederTableSeeder;
use Modules\Product\Database\Seeders\BrandSeederTableSeeder;
use Modules\Product\Database\Seeders\CategoryTableSeeder;
use Modules\Product\Database\Seeders\FlashSaleTableSeeder;
use Modules\Product\Database\Seeders\ProductAttributeOptionSeederTableSeeder;
use Modules\Product\Database\Seeders\ProductAttributeSeederTableSeeder;
use Modules\Product\Database\Seeders\ProductFlashSaleTableSeeder;
use Modules\Product\Database\Seeders\ProductImageTableSeeder;
use Modules\Product\Database\Seeders\ProductSeederTableSeeder;
use Modules\Product\Database\Seeders\SkuProductAttributeOptionSeederTableSeeder;
use Modules\Product\Database\Seeders\SpecificationTableSeeder;
use Modules\Seller\Database\Seeders\SellerProductTableSeeder;
use Modules\Seller\Database\Seeders\SellerTableSeeder;
use Modules\Shipping\Database\Seeders\CountryTableSeeder;
use Modules\Shipping\Database\Seeders\ShippingMethodCountriesTableSeeder;
use Modules\Shipping\Database\Seeders\ShippingMethodTableSeeder;
use Modules\User\Database\Seeders\BillingAddressTableSeeder;
use Modules\User\Database\Seeders\DeliveryAddressTableSeeder;
use Modules\User\Database\Seeders\UserTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(SellerTableSeeder::class);
        $this->call(SellerProductTableSeeder::class);
        $this->call(SpecificationTableSeeder::class);

        // $this->call(FlashSaleTableSeeder::class);
        // $this->call(PostTableSeeder::class);

        // $this->call(CountryTableSeeder::class);
        // $this->call(ShippingMethodTableSeeder::class);
        // $this->call(ShippingMethodCountriesTableSeeder::class);


        // $this->call(UserTableSeeder::class);
        // $this->call(AttributeSeederTableSeeder::class);
        // $this->call(BrandSeederTableSeeder::class);
        // $this->call(DiscountCouponTableSeeder::class);















        // $this->call(CategoryTableSeeder::class);
        // $this->call(ProductSeederTableSeeder::class);
        // $this->call(ProductImageTableSeeder::class);

        // $this->call(ProductFlashSaleTableSeeder::class);
        // 
        // $this->call(ProductAttributeSeederTableSeeder::class);
        // $this->call(ProductAttributeOptionSeederTableSeeder::class);
        // $this->call(SkuProductAttributeOptionSeederTableSeeder::class);

        // $this->call(OrderTableSeeder::class);
        // $this->call(OrderDetailTableSeeder::class);




        // $this->call(DeliveryAddressTableSeeder::class);
        // $this->call(BillingAddressTableSeeder::class);
    }
}
