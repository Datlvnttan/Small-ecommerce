<?php

namespace Modules\Product\Services;

use App\Helpers\Helper;
use Modules\Elastic\Services\ElasticService;
use Modules\Elastic\Services\ProductElasticService;
use Modules\Elastic\Services\SellerElasticService;
use Modules\Order\Repositories\Interface\OrderDetailRepositoryInterface;
use Modules\Product\Entities\Product;
use Modules\Product\Repositories\Interface\CategoryRepositoryInterface;
use Modules\Product\Repositories\Interface\ProductRepositoryInterface;

class ProductService
{
    protected $productRepositoryInterface;
    protected $orderDetailRepositoryInterface;
    protected $categoryRepositoryInterface;
    protected $productElasticService;
    protected $sellerElasticService;
    public const PER_PAGE = 30;
    public const DAYS_THRESHOLD  = 10;
    public function __construct(
        ProductRepositoryInterface $productRepositoryInterface,
        OrderDetailRepositoryInterface $orderDetailRepositoryInterface,
        CategoryRepositoryInterface $categoryRepositoryInterface,
        ProductElasticService $productElasticService,
        SellerElasticService $sellerElasticService
    ) {
        $this->productRepositoryInterface = $productRepositoryInterface;
        $this->orderDetailRepositoryInterface = $orderDetailRepositoryInterface;
        $this->categoryRepositoryInterface = $categoryRepositoryInterface;
        $this->productElasticService = $productElasticService;
        $this->sellerElasticService = $sellerElasticService;
    }

    protected function getPerPage()
    {
        return config('config.perPage');
    }
    public function all($isPerPage = true)
    {
        $perPage = null;
        if ($isPerPage) {
            $perPage = ProductService::PER_PAGE;
        }
        return $this->productRepositoryInterface->all(perPage: $perPage);
    }
    public function find($id)
    {
        return $this->productRepositoryInterface->find($id);
    }
    public function getProductDetails($id, $user)
    {

        return $this->productRepositoryInterface->getProductDetails($id);
    }

    public function getProductByTag($tag, $user)
    {

        switch ($tag) {
            case 'new':
                return $this->productRepositoryInterface->getNewProducts(ProductService::PER_PAGE);
            case 'hot':
                return $this->productRepositoryInterface->getHotProducts(ProductService::PER_PAGE);
            default:
                return $this->productRepositoryInterface->getSaleProducts(ProductService::PER_PAGE);
        }
    }
    public function getHotProductByBrandId($brandId, $user)
    {

        return $this->productRepositoryInterface->getHotProductByBrandId($brandId, ProductService::PER_PAGE);
    }
    public function getProductFlashSaleByFlashSaleId(int $flashSaleId, $isPaginate = false)
    {
        return $this->productRepositoryInterface->getProductFlashSaleByFlashSaleId($flashSaleId);
    }
    public function getFeedbackOverviewByProductId($productId)
    {
        return $this->orderDetailRepositoryInterface->getFeedbackOverviewByProductId($productId);
    }
    public function updateAggregateRatingBySkuId($skuId, $feedbackRating)
    {
        $product = $this->productRepositoryInterface->findProductBySkuId($skuId);
        if ($product) {
            $product->average_rating = (($product->average_rating * $product->total_rating) + $feedbackRating) / ($product->total_rating + 1);
            $product->total_rating += 1;
            $product->save();
        }
    }
    public function getFilterProduct(int $parentCategoryId = null, string $memberType = 'guest', bool $sale = false, bool $new = false, $sort = 'hot', bool $priceRange = false, int $minPrice = 0, int $maxPrice = null)
    {
        $category = null;
        if (isset($parentCategoryId)) {
            $category = $this->categoryRepositoryInterface->find($parentCategoryId);
        }
        return $this->productRepositoryInterface->getRecursiveProductsFilterPaginate(
            $category,

            $sale,
            $new,
            $sort,
            $priceRange,
            $minPrice,
            $maxPrice,
            ProductService::PER_PAGE,
        );
    }
    public function getProductByIds($productIdScores)
    {
        return $this->productRepositoryInterface->getByIdsAndSortByScore($productIdScores);
    }
    public function getAll(bool $isPerPage = true)
    {
        return $this->productRepositoryInterface->getAll($isPerPage ? ProductService::PER_PAGE : null);
    }
    public function search(string &$txtSearch = null, bool $fz = true, int $page = 1, array $categoryIds = null, int $brandId = null, string $sort = 'hot', bool $sale = false, bool $new = false, int  $minPrice = 0, int  $maxPrice = null, array $sellerIds = null, array $specificationValues = null, bool $searchBySku = false, bool $loadFilerSellerAndSpecification = false)
    {
        $txtSearchNew = $txtSearch;
        $change = false;
        if (isset($txtSearch)) {
            if ($this->checkExactSearchSyntax($txtSearchNew)) {
                $txtSearchNew = $this->removeFirstAndLastCharExactSearchSyntax($txtSearchNew);
                $fz = false;
            } else {
                if ($fz == true) {
                    $change = true;
                    $txtSearchNew = $this->productElasticService->sanitizeSearchQueryString($txtSearchNew, $categoryIds, $brandId);
                }
            }
        }
        // return $txtSearch;
        $results = $this->productElasticService->search($txtSearchNew, $fz,$page, $categoryIds, $brandId, $sort, $sale, $new, $minPrice, $maxPrice, $sellerIds, $specificationValues, false, $loadFilerSellerAndSpecification);
        if ($change) {
            $txtSearch = $txtSearchNew;
        }

        return $results;
    }
    protected function checkExactSearchSyntax(string $txtSearch)
    {
        $firstChar = substr($txtSearch, 0, 1);
        $lastChar = substr($txtSearch, -1);

        return $firstChar === '"' && $lastChar === '"';
    }
    function removeFirstAndLastCharExactSearchSyntax($txtSearch)
    {
        if (strlen($txtSearch) <= 2) {
            return '';
        }
        return substr($txtSearch, 1, -1);
    }
    public function getAllWithRelationship($isPaginate = true)
    {
        $perPage = null;
        if ($isPaginate) {
            $perPage = ProductService::PER_PAGE;
        }
        return $this->productRepositoryInterface->getAllWithRelationship(perPage: $perPage);
    }
}
