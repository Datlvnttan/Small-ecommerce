<?php

namespace Modules\Product\Services;

use Modules\Product\Repositories\Interface\CategoryRepositoryInterface;
use Modules\Product\Repositories\Interface\ProductRepositoryInterface;

class CategoryService
{
    protected $categoryRepositoryInterface;
    protected $productRepositoryInterface;
    public function __construct(CategoryRepositoryInterface $categoryRepositoryInterface, ProductRepositoryInterface $productRepositoryInterface)
    {
        $this->categoryRepositoryInterface = $categoryRepositoryInterface;
        $this->productRepositoryInterface = $productRepositoryInterface;
    }

    public function all()
    {
        return $this->categoryRepositoryInterface->all(perPage: config('config.perPage'));
    }

    public function getHotCategories()
    {
        $quantity = config('config.quantityPurchasedCalledHot');
        return $this->categoryRepositoryInterface->getHotCategories(1000);
    }
    public function showCategoryHierarchy($categoryId)
    {
        if (isset($categoryId))
            return $this->categoryRepositoryInterface->showCategoryHierarchy($categoryId);
        return null;
    }
    public function getSubcategoriesAndRecursiveProductsOrNewIfParentIsNull(int $parentCategoryId = null)
    {
        if (isset($parentCategoryId)) {
            $category = $this->categoryRepositoryInterface->find($parentCategoryId);
            return [
                'subCategories' => $category->subcategories,
                'products' => $category->recursiveProducts()->paginate(ProductService::PER_PAGE)
            ];
        } else {
            return [
                'subCategories' => $this->categoryRepositoryInterface->getRootCategories(),
                'products' => $this->productRepositoryInterface->getNewProducts(ProductService::PER_PAGE)
            ];
        }
    }
    public function getSubcategories(int $parentCategoryId = null)
    {
        if (isset($parentCategoryId)) {
            return $this->categoryRepositoryInterface->getDescendants($parentCategoryId);
        } else {
            return $this->getRootCategories();
        }
    }
    public function getRootCategories()
    {
        return $this->categoryRepositoryInterface->getRootCategories();
    }
    public function getDescendantsAndSelf(int $parentCategoryId)
    {
        return $this->categoryRepositoryInterface->getDescendantsAndSelf($parentCategoryId);
    }
    // public function getRecursiveProductsPaginate($categoryId)
    // {
    //     return 
    // }
    public function getRecursiveParentSiblingsAndSelf($categoryId, array $children = [])
    {
        $category = $this->categoryRepositoryInterface->find($categoryId);
        if (isset($category)) {
            if (count($children) > 0) {

                $category->children = $children;
            }

            $siblings = $category->siblings()->get()->toArray();
            $siblingsAndSelf = [$category];
            $siblingsAndSelf =  array_merge($siblingsAndSelf, $siblings);
            // return $siblingsAndSelf;
            $parentCategoryId = $category->parent_category_id;
            if (isset($parentCategoryId)) {
                return $this->getRecursiveParentSiblingsAndSelf($parentCategoryId, $siblingsAndSelf);
            } else {
                return $siblingsAndSelf;
            }
        }
        return null;
    }
}
