<?php

namespace Modules\Product\Repositories\Interface;

use App\Repositories\RepositoryInterface;

interface CategoryRepositoryInterface extends RepositoryInterface
{
    public function getHotCategories($quantity);
    public function showCategoryHierarchy($categoryId);
    public function getRootCategories();
    public function getSubcategories(int $parentCategoryId);
    public function getSubcategoriesAndRecursiveProducts(int $categoryId = null);
    public function getDescendants(int $categoryId);
    public function getDescendantsAndSelf(int $parentCategoryId);
}
