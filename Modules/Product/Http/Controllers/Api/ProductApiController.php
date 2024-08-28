<?php

namespace Modules\Product\Http\Controllers\Api;

use App\Helpers\Call;
use App\Helpers\Helper;
use App\Helpers\ResponseJson;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Modules\Elastic\Services\ProductElasticService;
use Modules\Elastic\Transformers\SellerAggregationsElasticSearchFillerTransformer;
use Modules\Elastic\Transformers\SellerNameInitialGroupAggregationsElasticSearchFillerTransformer;
use Modules\Elastic\Transformers\SpecificationNameGroupsAggregationsElasticSearchFilterTransformer;
use Modules\Elastic\Transformers\SpecificationsAggregationsElasticSearchFilterTransformer;
use Modules\Elastic\Transformers\SpecificationValueAggregationsElasticSearchFilterTransformer;
use Modules\Product\Entities\Product;
use Modules\Product\Http\Requests\FilterProductRequest;
use Modules\Product\Http\Requests\ProductSearchRequest;
use Modules\Product\Services\CategoryService;
use Modules\Product\Services\ProductImageService;
use Modules\Product\Services\ProductService;
use Modules\Product\Transformers\ItemProductElasticSearchTransformer;
use Modules\Product\Transformers\ItemProductTransformer;
use Modules\Product\Transformers\ProductDetailsTransformer;

class ProductApiController extends Controller
{
    protected $productService;
    protected $categoryService;
    protected $productImageService;
    protected $productElasticService;

    public function __construct(ProductService $productService, ProductImageService $productImageService, CategoryService $categoryService, ProductElasticService $productElasticService)
    {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
        $this->productImageService = $productImageService;
        $this->productElasticService = $productElasticService;
    }
    /**
     * Lấy danh sách tất cả sản phẩm hoặc tùy thuộc vào tag.
     * @return Renderable
     */
    public function index(Request $request)
    {
        return Call::TryCatchResponseJsonFractalManager(function ($fractal) use ($request) {
            $tag = $request->get('tag');
            if (!isset($tag)) {
                $products = $this->productService->getAll(true);
            } else {
                $user = Auth::user();
                $products = $this->productService->getProductByTag($tag, $user);
                // return $products;
            }
            // return $products;
            $resource = new Collection($products, new ItemProductTransformer());
            $products = $fractal->createData($resource)->toArray();
            return ResponseJson::success(data: $products['data']);
        });
    }



    /**
     * Lấy danh sách sản phẩm hot theo productId
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function getHotProductByBrandId(Request $request)
    {
        return Call::TryCatchResponseJsonFractalManager(function ($fractal) use ($request) {
            $brandId = $request->input('brandId');
            $user = Auth::user();
            $products = $this->productService->getHotProductByBrandId($brandId, $user);
            $resource = new Collection($products, new ItemProductTransformer());
            $products = $fractal->createData($resource)->toArray();
            return ResponseJson::success(data: $products['data']);
        });
    }



    /**
     * Lấy dữ liệu product details.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return Call::TryCatchResponseJsonFractalManager(function ($fractal) use ($id) {
            $user = Auth::user();
            $product = $this->productService->getProductDetails($id, $user);
            if (!isset($product)) {
                return ResponseJson::error('Product not found');
            }
            $feedbackOverview = $this->productService->getFeedbackOverviewByProductId($id);
            $categoryHierarchies = $this->categoryService->showCategoryHierarchy($product->category_id);
            $resource = new Item($product, new ProductDetailsTransformer());
            $productTransformer = $fractal->createData($resource)->toArray();
            return ResponseJson::success(data: [
                'categoryHierarchies' => $categoryHierarchies,
                'product' => $productTransformer['data'],
                'feedbackOverview' => $feedbackOverview,
                // 'productImages'=>$productImages,
                // 'product'=>$product,
            ]);
        });
    }
    public function filterProduct(FilterProductRequest $request)
    {
        // return $request->all();
        return Call::TryCatchResponseJsonFractalManager(function ($fractal) use ($request) {
            $parentCategoryId = $request->input('parentCategoryId');
            // return $parentCategoryId;
            $priceRange = $request->input('priceRange');
            $minPrice = $request->input('minPrice', 0);
            $maxPrice = $request->input('maxPrice');
            $sale = $request->input('sale');
            $new = $request->input('new');
            $sort = $request->input('sort'); //hot,rating,p-lth,p-htl,az,za
            $products = null;
            $memberType = 'guest';
            // return $maxPrice;
            if (Auth::check()) {
                $user = Auth::user();
                $memberType = 'member_' . $user->member_type;
            }
            $products = $this->productService->getFilterProduct($parentCategoryId, $memberType, $sale, $new, $sort, $priceRange, $minPrice, $maxPrice);
            $resource = new Collection($products, new ItemProductTransformer());
            $productsTransformer = $fractal->createData($resource)->toArray();
            return ResponseJson::success(data: [
                'data' => $productsTransformer['data'],
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),

            ]);
        });
    }
    public function searchOld(ProductSearchRequest $request)
    {
        return Call::TryCatchResponseJsonFractalManager(function ($fractal) use ($request) {
            // return $request->all();
            $q = $request->get('q');
            $fz =  $request->get('fz');
            $parentCategoryId = $request->get('c');
            $brandId = $request->get('b');
            $minPrice = $request->input('minPrice', 0);
            $maxPrice = $request->input('maxPrice');
            $sale = $request->input('sale');
            $new = $request->input('new');
            $sort = $request->input('sort'); //hot,rating,p-lth,p-htl,az,za
            $page = $request->get('page', 1);
            $categoryIds = null;
            if (isset($parentCategoryId)) {
                $categoryDescendantsAndSelf = $this->categoryService->getDescendantsAndSelf($parentCategoryId);
                if (isset($categoryDescendantsAndSelf)) {
                    $categoryIds =  $categoryDescendantsAndSelf->pluck('id')->toArray();
                }
            }
            $txtSearch = $q;
            $data = $this->productService->search($txtSearch, $fz, $page, $categoryIds, $brandId, $sort, $sale, $new, $minPrice, $maxPrice);
            if (isset($data)) {
                if (count($data['products']) == 0) {
                    return ResponseJson::success(data: []);
                }
                $memberType = Helper::getMemberType();
                $result  = new Collection($data['products'], new ItemProductElasticSearchTransformer($memberType));
                $productsTransformer = $fractal->createData($result)->toArray();
                $productsTransformer['total'] = $data['total'];
                $productsTransformer['current_page'] = $data['currentPage'];
                $productsTransformer['last_page'] = $data['lastPage'];
                $productsTransformer['q_old'] = $q;
                $productsTransformer['q_new'] = $txtSearch;
                return ResponseJson::success(data: $productsTransformer);
            }
            return ResponseJson::failed('Failed to search');
        });
    }

    public function search(ProductSearchRequest $request)
    {
        return Call::TryCatchResponseJsonFractalManager(function ($fractal) use ($request) {
            // return $request->all();
            $q = $request->get('q');
            $fz =  $request->get('fz');
            $lf =  $request->input('lf');
            $parentCategoryId = $request->get('c');
            $brandId = $request->get('b');
            $specificationValues = $request->get('sv');
            $sellerIds = $request->get('sn');
            $minPrice = $request->input('minPrice', 0);
            $maxPrice = $request->input('maxPrice');
            $sale = $request->input('sale');
            $new = $request->input('new');
            $sort = $request->input('sort'); //hot,rating,p-lth,p-htl,az,za
            $page = $request->get('page');
            $searchBySku = $request->input('sbs');
            if (!isset($page)) {
                $page = 1;
            }
            $categoryIds = null;
            if (isset($parentCategoryId)) {
                $categoryDescendantsAndSelf = $this->categoryService->getDescendantsAndSelf($parentCategoryId);
                if (isset($categoryDescendantsAndSelf)) {
                    $categoryIds =  $categoryDescendantsAndSelf->pluck('id')->toArray();
                }
            }
            $txtSearch = $q;
            $data = $this->productService->search($txtSearch, $fz, $page, $categoryIds, $brandId, $sort, $sale, $new, $minPrice, $maxPrice, $sellerIds, $specificationValues,$searchBySku, $lf);
            if (isset($data)) {
                if (count($data['products']) == 0) {
                    return ResponseJson::success(data: [
                        'q_old' => $q,
                        'q_new' => $txtSearch,
                        'products' => [],
                        'aggregations' => [],
                    ]);
                }
                $memberType = Helper::getMemberType();
                $productsResult  = new Collection($data['products'], new ItemProductElasticSearchTransformer($memberType));
                $productsTransformer = $fractal->createData($productsResult)->toArray();
                $productsTransformer['total'] = $data['total'];
                $productsTransformer['current_page'] = $data['currentPage'];
                $productsTransformer['last_page'] = $data['lastPage'];
                $productsTransformer['q_old'] = $q;
                $productsTransformer['q_new'] = $txtSearch;
                $dataFinal = [
                    'products' => $productsTransformer,
                ];
                if ($lf == true) {
                    $aggregations = $data['aggregations'];

                    $fillerSpecificationNameGroupsResult  = new Collection($aggregations['specifications']['specification_name_group']['buckets'], new SpecificationNameGroupsAggregationsElasticSearchFilterTransformer($specificationValues));
                    $aggregationsSpecificationNameGroupsTransformer = $fractal->createData($fillerSpecificationNameGroupsResult)->toArray();


                    $fillerSellerResult  = new Collection($aggregations['sellers']['seller_name_initial_group']['buckets'], new SellerNameInitialGroupAggregationsElasticSearchFillerTransformer($specificationValues));
                    $aggregationsSellerNameInitialsTransformer = $fractal->createData($fillerSellerResult)->toArray();
                    $dataFinal['aggregations'] = [
                        // 'specifications' => $aggregationsSpecificationsTransformer['data'],
                        'specificationNameGroup' => $aggregationsSpecificationNameGroupsTransformer['data'],
                        'sellers' => $aggregationsSellerNameInitialsTransformer['data'],
                    ];
                }

                return ResponseJson::success(data: $dataFinal);
            }
            return ResponseJson::failed('Failed to search');
        });
    }
}
