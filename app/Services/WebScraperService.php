<?php

namespace App\Services;

use App\Helpers\Helper;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Laravel\Dusk\Browser;
use Modules\External\Services\WebikeExternalApiService;
use Modules\Product\Entities\Attribute;
use Modules\Product\Entities\Brand;
use Modules\Product\Entities\Category;
use Modules\Product\Entities\FlashSale;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductAttribute;
use Modules\Product\Entities\ProductAttributeOption;
use Modules\Product\Entities\ProductFlashSale;
use Modules\Product\Entities\ProductImage;
use Modules\Product\Entities\Sku;
use Modules\Product\Entities\SkuProductAttributeOption;
use voku\helper\HtmlDomParser;
use Illuminate\Support\Facades\Http;
use Modules\Product\Entities\specification;
use Modules\Seller\Entities\Seller;
use Modules\Seller\Entities\SellerProduct;

class WebScraperService
{

    public function scraperProductWebikeDetail(string $productIdCode)
    {
        $client = new Client();
        $response = $client->get("https://shop.webike.vn/products/{$productIdCode}.html");
        $html = (string) $response->getBody();
        $dom = HtmlDomParser::str_get_html($html);
        $content = $dom->findOneOrFalse('#content');
        if ($content === false)
            return;
        $elementProductName = $content->findOneOrFalse('.product-name');
        $brandName = $elementProductName->findOneOrFalse('.brand')->text();
        $productName = $elementProductName->findOneOrFalse('.name')->text();
        $product = Product::where('product_name', '=', $productName)->first();
        if (isset($product)) {
            return;
        }
        $ul = $content->findOneOrFalse('div.gps-area ul');
        $categoryElements = $ul->findMulti('li');
        $category = null;
        $parentCategoryId = null;
        foreach ($categoryElements as $categoryElement) {
            $aElement = $categoryElement->findOneOrFalse('a');
            $href = $aElement->getAttribute('href');
            if (strpos($href, 'bm/top') !== false) {
                $categoryName = $aElement->findOneOrFalse('span')->text();
                if (isset($categoryName)) {
                    $category = Category::where('category_name', '=', $categoryName)->first();
                    if (!isset($category))
                        $category = Category::create([
                            'category_name' => $categoryName,
                            'parent_category_id' => $parentCategoryId,
                        ]);
                    $parentCategoryId = $category->id;
                }
            } else {
                break;
            }
        }
        $brand = Brand::where('brand_name', '=', $brandName)->first();
        if (!isset($brand))
            $brand = Brand::create([
                'brand_name' => $brandName,
            ]);

        $descBodys = $dom->findMulti('.descBody');
        $describe = null;
        if (isset($descBodys[0]))
            $describe = $descBodys[0]->text();
        $detail = null;
        if (isset($descBodys[3]))
            $detail = $descBodys[3]->text();
        $shoppingPoint = fake()->numberBetween(1, 1000);
        $averageRating = fake()->randomFloat(2, 0, 5);
        if ($averageRating > 0)
            $totalRating = fake()->numberBetween(0, 1000);
        else
            $totalRating = 0;
        $product = Product::create([
            'product_name' => $productName,
            'describe' => $describe,
            'detail' => $detail,
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            // 'cover_image' => 'webike_25625112.jpg',
            'shipping_point' => $shoppingPoint,
            'average_rating' => $averageRating,
            'total_rating' => $totalRating,
            'total_quantity_sold' => fake()->numberBetween(0, 100000),
            'created_at' => fake()->dateTimeBetween('-40 days')
        ]);
        if (fake()->boolean(20)) {
            $flashSale = FlashSale::first();
            ProductFlashSale::create([
                'product_id' => $product->id,
                'flash_sale_id' => $flashSale->id,
                'discount' => fake()->randomFloat(2, 0.03, 0.4)
            ]);
        }
        $elementDivProductImage = $content->find('#product-img-block');
        // $elementUl = $elementDivProductImage->find('ul.item_list_block');
        $elementImages = $elementDivProductImage->findMulti('*[id*=pro_img]');
        $ii = 0;
        foreach ($elementImages as $elementImage) {
            $imageUrl = $elementImage->getAttribute('value');
            $fileName = $product->id . '_';
            if ($ii == 0) {
                $fileName .= 'default.jpg';
            } else {
                $fileName .= (time() . Helper::randomOTPNumeric(10)) . '.jpg';
            }
            try {
                $pathImage = "modules/product/img/{$fileName}";
                $checkSave = Helper::saveImage($imageUrl, $pathImage);
                if ($checkSave) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_name' => $fileName,
                    ]);
                }
            } catch (\Exception $e) {
            }
            $ii++;
        }

        $elementSelects = $content->findMulti('select[name="selectedOptions"]');
        $selectCount = count($elementSelects);

        if ($selectCount > 0) {
            $productAttributeOptionIds = [];
            foreach ($elementSelects as $elementSelect) {
                $elementOptions = $elementSelect->findMulti('option');
                $optionCount = count($elementOptions);
                if ($optionCount > 0) {
                    $attributeName = $elementOptions[0]->text();
                    // array_push($matrixOptionValues,array_slice($elementOptions, 1));
                    $attributeName = str_replace("Please select", "", $attributeName);
                    $attributeName = str_replace("a", "", $attributeName);
                    if (strlen($attributeName) == 0) {
                        $attributeName = 'Option';
                    }
                    $attributeName = ucfirst($attributeName);
                    $attribute = Attribute::where('attribute_name', $attributeName)->first();
                    if (!isset($attribute)) {
                        $attribute = Attribute::create([
                            'attribute_name' => $attributeName,
                        ]);
                    }
                    $productAttribute = ProductAttribute::create([
                        'product_id' => $product->id,
                        'attribute_id' => $attribute->id,
                    ]);
                    $attribute['webikeId'] = $elementSelect->getAttribute('id');
                    $attribute['dbId'] = $productAttribute->id;
                    $options = [];
                    for ($i = 1; $i < $optionCount; $i++) {
                        $elementOption = $elementOptions[$i];
                        $optionName = $elementOption->text();
                        $productAttributeOption = ProductAttributeOption::create([
                            'product_attribute_id' => $productAttribute->id,
                            'option_name' => $optionName,
                        ]);
                        array_push($options, $productAttributeOption->id);
                    }
                    array_push($productAttributeOptionIds, $options);
                }
            }
            $resultOptions = [];
            $this->arraySkuProduct($productAttributeOptionIds, 0, [], $resultOptions);
            $count = count($resultOptions);
            $default = fake()->numberBetween(0, $count);
            for ($i = 0; $i < $count; $i++) {
                sort($resultOptions[$i]);
                $productPartNumber = implode("-", $resultOptions[$i]);
                $skus = Sku::factory()->count(1)->create([
                    'product_id' => $product->id,
                    'default' => ($i == $default),
                    'product_part_number' => $productPartNumber,
                ]);
                foreach ($resultOptions[$i] as $productAttributeOptionId) {
                    SkuProductAttributeOption::create([
                        'product_attribute_option_id' => $productAttributeOptionId,
                        'sku_id' => $skus[0]->id
                    ]);
                }
            }
        } else {
            Sku::factory()->count(1)->create([
                'product_id' => $product->id,
                'default' => true
            ]);
        }

        // $webikeApiService = new WebikeExternalApiService();
        // $this->browse(function (Browser $browser) use ($productIdCode) {
        //     $browser->visit("https://shop.webike.vn/products/{$productIdCode}.html")
        //         ->waitFor('.content')
        //         ->each(function ($content) use ($productIdCode) {
        //         });
        // });
    }

    private function arraySkuProduct($arrays, $currentIndex = 0, $currentCombination = [], &$result = [],$currentCombination2 = [],mixed &$resultOptionNames = [])
    {
        if ($currentIndex == count($arrays)) {
            $result[] = $currentCombination;
            $resultOptionNames[] = $currentCombination2;
            return;
        }
        foreach ($arrays[$currentIndex] as $value) {
            $this->arraySkuProduct($arrays, $currentIndex + 1, array_merge($currentCombination, [$value->id]), $result,array_merge($currentCombination2, [$value->option_name]),$resultOptionNames);
        }
    }
    protected function generateAttributes(array $attributes, $arrIndex, array $resultParams = [], $resultOptions = [])
    {
        $attributeIndex = 0;
        $options = [];
        $paramOptions = [];
        $countArrIndex = count($arrIndex);
        for ($i = 0; $i < $countArrIndex; $i++) {
            $option = $attributes[$attributeIndex]['options'][$arrIndex[$i]];
            $pramWebikeOption[$attributes[$attributeIndex]['webikeId']] = $attributes[$attributeIndex]['pramWebikeOptions'][$arrIndex[$i]];
            array_push($options, $option);
            array_push($paramOptions, $pramWebikeOption);

            for ($j = 1; $j < $attributes[$attributeIndex]['countOption']; $j++) {
                $arrIndex[$i] = $arrIndex[$i] + $j;
                $this->generateAttributes($attributes, $arrIndex, $resultParams, $resultOptions);
                $arrIndex[$i] = $arrIndex[$i] - $j;
            }
            $attributeIndex++;
        }
        array_push($resultParams, $paramOptions);
        array_push($resultOptions, $options);
    }

    public function scraperProductTikiDetails($url, $flashSaleId)
    {
        $client = new Client();
        $response = $client->get($url);
        $html = (string) $response->getBody();
        $dom = HtmlDomParser::str_get_html($html);
        $content = $dom->findOneOrFalse('.styles__Wrapper-sc-pmgxyr-0');
        if ($content === false)
            return;
        $elementProductName = $content->findOneOrFalse('.Title__TitledStyled-sc-c64ni5-0.iXccQY');
        $productName = $elementProductName->text();
        $product = Product::where('product_name', '=', $productName)->first();
        if (isset($product)) {
            return;
        }
        $divCategory = $dom->findOneOrFalse('div.breadcrumb');
        $categoryElements = $divCategory->findMulti('a');
        $category = null;
        $parentCategoryId = null;
        foreach ($categoryElements as $categoryElement) {
            $spanElement = $categoryElement->findOneOrFalse('span');
            $categoryName = $spanElement->text();
            $href = $categoryElement->getAttribute('href');
            if ($categoryName != 'Trang chủ' && $href != '#') {
                $category = Category::where('category_name', '=', $categoryName)->first();
                if (!isset($category))
                    $category = Category::create([
                        'category_name' => $categoryName,
                        'parent_category_id' => $parentCategoryId,
                    ]);
                $parentCategoryId = $category->id;
            }
        }
        $brand = null;
        $brandElement = $content->findOneOrFalse('a[data-view-id="pdp_details_view_brand"]');
        if ($brandElement === false) {
            $brand = Brand::inRandomOrder()->first();
        } else {
            $brandName = $brandElement->text();
            $brand = Brand::where('brand_name', '=', $brandName)->first();
            if (!isset($brand))
                $brand = Brand::create([
                    'brand_name' => $brandName,
                ]);
        }

        // $descBodys = $content->findMulti('div.HighlightInfo__HighlightInfoContentStyled-sc-1pr13u3-0.iVYaat');
        // $detail = $content->findOne('.ToggleContent__View-sc-fbuwol-0.imwRtb')->outerHtml();
        $shoppingPoint = fake()->numberBetween(1, 1000);
        $totalQuantitySoldElement = $content->findOne(('div.styles__StyledQuantitySold-sc-1onuk2l-3.eWJdKv'));
        $totalQuantitySold = $totalQuantitySoldElement->text();
        $totalQuantitySold = intval(str_replace('Đã bán ', '', $totalQuantitySold));
        $averageRating = fake()->randomFloat(2, 0, 5);
        if ($averageRating > 0)
            $totalRating = fake()->numberBetween(0, 1000);
        else
            $totalRating = 0;
        $product = Product::create([
            'product_name' => $productName,
            'describe' => fake()->sentence(fake()->numberBetween(7, 20)),
            'detail' => fake()->text(fake()->numberBetween(100, 400)),
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            // 'cover_image' => 'webike_25625112.jpg',
            'shipping_point' => $shoppingPoint,
            'average_rating' => $averageRating,
            'total_rating' => $totalRating,
            'total_quantity_sold' => $totalQuantitySold,
            'created_at' => fake()->dateTimeBetween('-40 days', '+10days')
        ]);
        if (fake()->boolean(20)) {

            ProductFlashSale::create([
                'product_id' => $product->id,
                'flash_sale_id' => $flashSaleId,
                'discount' => fake()->randomFloat(2, 0.03, 0.4)
            ]);
        }
        $elementImages = $content->findMulti('.style__ThumbnailItemStyled-sc-g98s1e-1.jWvPKd');
        $ii = 0;
        $rangImg = fake()->numberBetween(1, 3);
        foreach ($elementImages as $elementImage) {
            if ($ii > $rangImg) {
                break;
            }
            $imageUrl = $elementImage->findOne('img')->getAttribute('src');
            $fileName = $product->id . '_';
            if ($ii == 0) {
                $fileName .= 'default.jpg';
            } else {
                $fileName .= (time() . Helper::randomOTPNumeric(10)) . '.jpg';
            }
            try {
                $pathImage = ProductImage::PATH_PRODUCT_IMAGE . "{$fileName}";
                $checkSave = Helper::saveImage($imageUrl, $pathImage);
                if ($checkSave) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_name' => $fileName,
                    ]);
                }
            } catch (\Exception $e) {
            }
            $ii++;
        }
        $selectCount = fake()->numberBetween(0, 5);
        if ($selectCount > 0) {
            $productAttributeOptionIds = [];
            for ($i = 0; $i < $selectCount; $i++) {
                $productAttribute = $this->fakeProductAttribute($product->id);
                $options = $this->fakeProductAttributeOptions($productAttribute->id);
                array_push($productAttributeOptionIds, $options);
            }
            $resultOptions = [];
            $this->arraySkuProduct($productAttributeOptionIds, 0, [], $resultOptions);
            $count = count($resultOptions);
            $default = fake()->numberBetween(0, $count);
            for ($i = 0; $i < $count; $i++) {
                sort($resultOptions[$i]);
                $productPartNumber = implode("-", $resultOptions[$i]);
                $skus = Sku::factory()->count(1)->create([
                    'product_id' => $product->id,
                    'default' => ($i == $default),
                    'product_part_number' => $productPartNumber,
                ]);
                foreach ($resultOptions[$i] as $productAttributeOptionId) {
                    SkuProductAttributeOption::create([
                        'product_attribute_option_id' => $productAttributeOptionId,
                        'sku_id' => $skus[0]->id
                    ]);
                }
            }
        } else {
            Sku::factory()->count(1)->create([
                'product_id' => $product->id,
                'default' => true
            ]);
        }
    }
    protected function fakeProductAttribute($productId)
    {
        $attributeId = null;
        do {
            $attribute = Attribute::inRandomOrder()->first();
            $attributeId = $attribute->id;
        } while (ProductAttribute::where('product_id', $productId)
            ->where('attribute_id', $attributeId)->exists()
        );

        return ProductAttribute::create([
            'attribute_id' => $attributeId,
            'product_id' => $productId,
        ]);
    }

    public function buildData($data, $flashSaleId, $brandId, $check)
    {
        if ($check) {
            $product = Product::where('product_name', $data[1])->first();
            if (isset($product)) {
                return true;
            }
        }
        return Helper::DBTransaction(function () use ($data, $flashSaleId, $brandId) {
            $data[7] = str_replace('"', '', $data[7]);
            $categoryNames = explode('&gt;', $data[7]);
            $category = null;
            $parentCategoryId = null;
            foreach ($categoryNames as $categoryName) {
                $categoryName = trim($categoryName);
                $category = Category::where('category_name', '=', $categoryName)->first();
                if (!isset($category))
                    $category = Category::create([
                        'category_name' => $categoryName,
                        'parent_category_id' => $parentCategoryId,
                    ]);
                $parentCategoryId = $category->id;
            }


            $shoppingPoint = fake()->numberBetween(1, 1000);
            $totalQuantitySold = fake()->numberBetween(0, 100000);
            $averageRating = fake()->randomFloat(2, 0, 5);
            if ($averageRating > 0)
                $totalRating = fake()->numberBetween(0, 1000);
            else
                $totalRating = 0;
            $product = Product::create([
                'product_name' => $data[1],
                'describe' => fake()->sentence(fake()->numberBetween(7, 20)),
                'detail' => $data[6],
                'brand_id' => $brandId,
                'category_id' => $category->id,
                'cover_image' => $data[5],
                'shipping_point' => $shoppingPoint,
                'average_rating' => $averageRating,
                'total_rating' => $totalRating,
                'total_quantity_sold' => $totalQuantitySold,
                'created_at' => fake()->dateTimeBetween('-40 days', '+10days')
            ]);
            if (fake()->boolean(20)) {

                ProductFlashSale::create([
                    'product_id' => $product->id,
                    'flash_sale_id' => $flashSaleId,
                    'discount' => fake()->randomFloat(2, 0.03, 0.4)
                ]);
            }
            $selectCount = 0;
            if (fake()->boolean(80)) {
                $selectCount = fake()->numberBetween(0, 2);
            } else {
                $selectCount = fake()->numberBetween(2, 5);
            }
            if ($selectCount > 0) {
                $productAttributeOptionIds = [];
                for ($i = 0; $i < $selectCount; $i++) {
                    $productAttribute = $this->fakeProductAttribute($product->id);
                    $options = $this->fakeProductAttributeOptions($productAttribute->id);
                    array_push($productAttributeOptionIds, $options);
                }
                $resultOptions = [];
                $resultOptionNames = [];
                $this->arraySkuProduct($productAttributeOptionIds, 0, [], $resultOptions,[],$resultOptionNames);
                $count = count($resultOptions);
                $default = fake()->numberBetween(0, $count - 1);
                for ($i = 0; $i < $count; $i++) {
                    sort($resultOptions[$i]);
                    $productPartNumber = implode("-", $resultOptions[$i]);
                    $option = implode(", ", $resultOptionNames[$i]);
                    $skus = Sku::factory()->count(1)->create([
                        'product_id' => $product->id,
                        'option' => $option,
                        'default' => ($i == $default),
                        'product_part_number' => $productPartNumber,
                    ]);
                    foreach ($resultOptions[$i] as $productAttributeOptionId) {
                        SkuProductAttributeOption::create([
                            'product_attribute_option_id' => $productAttributeOptionId,
                            'sku_id' => $skus[0]->id
                        ]);
                    }
                }
            } else {
                Sku::factory()->count(1)->create([
                    'product_id' => $product->id,
                    'default' => true
                ]);
            }
            return false;
        });
    }
    public function getSpecifications($url, $userId, int &$number)
    {
        Helper::DBTransaction(function () use ($url, $userId, &$number) {
            $params = explode('.html', $url);
            $data = explode('-p', $params[0]);
            $pid = $data[count($data) - 1];
            $response = Http::get("https://tiki.vn/api/v2/products/{$pid}");
            if ($response->successful()) {
                $productJson = $response->json();
                $product = Product::where('product_name', $productJson['name'])->first();
                if (isset($product)) {
                    $currentSeller = $productJson['current_seller'];
                    $seller = null;
                    if (isset($currentSeller)) {
                        $currentSeller['name'] = trim($currentSeller['name']);
                        $seller = Seller::where('seller_name', 'LIKE', $currentSeller['name'] . '%')->first();
                        if (!isset($seller)) {
                            $locked = fake()->boolean(5);
                            $seller = Seller::create([
                                'seller_name' => $currentSeller['name'] . "_{$number}",
                                'logo' => $currentSeller['logo'],
                                'email' => fake()->email(),
                                'user_id' => $userId,
                                'locked' => $locked,
                                'created_at' => fake()->dateTimeBetween('-50days'),
                            ]);
                            $number += 1;
                        }
                    } else {
                        $seller = Seller::inRandomOrder()->first();
                    }
                    while (SellerProduct::where('seller_id', $seller->id)->where('product_id', $product->id)->exists()) {
                        $seller = Seller::where('id', '<>', $seller->id)->inRandomOrder()->first();
                    }
                    SellerProduct::create([
                        'product_id' => $product->id,
                        'seller_id' => $seller->id,
                        'hidden' => fake()->boolean(3),
                    ]);
                    if (Specification::where('product_id', $product->id)->exists()) {
                        return;
                    }
                    $specifications = $productJson['specifications'];
                    if (isset($specifications) && count($specifications) > 0 && isset($specifications[0])) {
                        $attributes = $specifications[0]['attributes'];
                        if (isset($attributes)) {
                            $data = [];
                            foreach ($attributes as $attribute) {
                                $data['specification_name'] = $attribute['name'];
                                $data['specification_value'] = $attribute['value'];
                                $data['product_id'] = $product->id;
                                $data['created_at'] = now();
                                $data['updated_at'] = now();
                            }
                            try {
                                Specification::insert($data);
                            } catch (\Throwable $th) {
                                $attributesNumber = fake()->numberBetween(1, 7);
                                Specification::factory()->count($attributesNumber)->create([
                                    'product_id' => $product->id
                                ]);
                            }
                            return;
                        }
                    }
                    $attributesNumber = fake()->numberBetween(1, 7);
                    Specification::factory()->count($attributesNumber)->create([
                        'product_id' => $product->id
                    ]);
                    return;
                }
            } else {
                throw new \Exception('false specification');
            }
        });
    }
    protected function fakeProductAttributeOptions($productAttributeId)
    {
        $ran = null;
        $ranboolean = fake()->boolean(90);
        if ($ranboolean) {
            $ran = fake()->numberBetween(1, 4);
        } else {
            $ran = fake()->numberBetween(4, 6);
        }
        $options = [];
        for ($i = 0; $i < $ran; $i++) {
            do {
                $optionName = fake()->words(random_int(2, 6), true);
            } while (ProductAttributeOption::where('product_attribute_id', $productAttributeId)
                ->where('option_name', $optionName)->exists()
            );
            $productAttributeOption = ProductAttributeOption::create([
                'product_attribute_id' => $productAttributeId,
                'option_name' => ucfirst($optionName),
            ]);
            array_push($options, $productAttributeOption);
        }
        return $options;
    }
}
