<?php

namespace Modules\Product\Http\Controllers\Api;

use App\Helpers\Call;
use App\Helpers\ResponseJson;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Product\Services\CategoryService;

class CategoryApiController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $tag = $request->get('tag');
        return Call::TryCatchResponseJson(function () use ($tag) {
            if (isset($tag))
                $categories = $this->categoryService->getHotCategories();
            else
                $categories = $this->categoryService->all();
            return ResponseJson::success(data: $categories);
        });
    }
    public function getSubcategories(int $parentCategoryId = null )
    {
        return Call::TryCatchResponseJson(function () use ($parentCategoryId) {
            $category = $this->categoryService->getSubcategories($parentCategoryId);
            return ResponseJson::success(data: $category);
        });
    }
    public function getRecursiveParentSiblingsAndSelf(int $categoryId = null)
    {
        return Call::TryCatchResponseJson(function () use ($categoryId) {
            $categories = null;
            if(isset($categoryId))
            {
                $categories = $this->categoryService->getRecursiveParentSiblingsAndSelf($categoryId);
            }
            else
            {
                $categories = $this->categoryService->getRootCategories();
            }
            return ResponseJson::success(data: $categories);
        });
    }
}
