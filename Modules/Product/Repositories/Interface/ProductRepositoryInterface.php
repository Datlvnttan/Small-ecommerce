<?php

namespace Modules\Product\Repositories\Interface;

use App\Repositories\RepositoryInterface;
use Modules\Product\Entities\Category;

interface ProductRepositoryInterface extends RepositoryInterface
{
    public function getNewProducts(int $perPage = null);
    public function getHotProducts(int $perPage = null);
    public function getSaleProducts(int $perPage = null);
    public function getHotProductByBrandId($brandId, int $perPage = null);
    public function getProductDetails($id);
    public function findProductBySkuId($skuId);
    public function getRecursiveProductsFilterPaginate(Category $category = null, bool $sale = false, bool $new = false, string $sort = 'hot', bool $priceRange = false, int $priceMin = 0, int $priceMax = null, int $perPage = 10);
    public function getProductFlashSaleByFlashSaleId($flashSaleId, $isPaginate = false);
    public function getByIdsAndSortByScore(array $productIdScores);
    public function getAll(int $perPage = null);
    public function getAllWithRelationship($page = null, $perPage = null);
}
