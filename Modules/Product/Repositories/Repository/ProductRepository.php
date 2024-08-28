<?php

namespace Modules\Product\Repositories\Repository;

use App\Repositories\EloquentRepository;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\DB;
use Modules\Product\Entities\Category;
use Modules\Product\Entities\Product;
use Modules\Product\Repositories\BaseProductQueryRepository;
use Modules\Product\Repositories\Interface\ProductRepositoryInterface;

class ProductRepository extends BaseProductQueryRepository implements ProductRepositoryInterface
{
    public function getModel()
    {
        return \Modules\Product\Entities\Product::class;
    }
    protected function getQueryModelJoin($model)
    {
        return $model->join('skus', 'products.id', '=', 'skus.product_id');
    }
    public function getNewProducts(int $perPage = null)
    {
        return $this->queryJoinSkuDefaultIsPaginate(function ($query) {
            return $query->whereIsNew();
        }, null, $perPage);
    }
    public function getHotProducts(int $perPage = null)
    {
        return $this->queryJoinSkuDefaultIsPaginate(function ($query) {
            return $query->whereIsHot();
        }, null, $perPage);
    }
    public function getSaleProducts(int $perPage = null)
    {
        return $this->queryJoinSkuDefaultIsPaginate(function ($query) {
            return $query->where("{$this->memberType}_discount", '>', 0);
        }, null, $perPage);
    }
    protected function queryJoinSkuDefault(Closure $buildQuery = null, array $select = null)
    {
        return $this->queryDataProduct(function ($query) use ($buildQuery) {
            $query = $query->where('skus.default', true);
            if (isset($buildQuery))
                $query = $buildQuery($query);
            return $query;
        }, $select);
    }
    public function getAll(int $perPage = null)
    {
        if (!isset($perPage)) {
            ini_set('memory_limit', '3G');
        }
        return $this->queryJoinSkuDefaultIsPaginate(select: [
            'products.describe',
            'products.detail',
            'skus.guest_price',
            'skus.guest_discount',
            'skus.member_retail_price',
            'skus.member_retail_discount',
            'skus.member_wholesale_price',
            'skus.member_wholesale_discount',
        ], perPage: $perPage);
    }
    protected function queryJoinSkuDefaultIsPaginate(Closure $buildQuery = null, array $select = null, int $perPage = null)
    {
        $query = $this->queryJoinSkuDefault($buildQuery, $select);
        if (isset($perPage)) {
            return $query->paginate($perPage);
        } else {
            return $query->get();
        }
    }
    /**
     * Summary of getHotProductByBrandId
     * @param mixed $brandId
     * @return void
     */
    public function getHotProductByBrandId($brandId, int $perPage = null)
    {
        return $this->queryJoinSkuDefaultIsPaginate(function ($query) use ($brandId) {
            return $query->where("{$this->model->table}.brand_id", '=', $brandId)
                ->orderBy('total_quantity_sold', 'DESC');
        }, null, $perPage);
    }
    public function getProductFlashSaleByFlashSaleId($flashSaleId, $isPaginate = false)
    {
        return $this->queryJoinSkuDefaultIsPaginate(function ($query) use ($flashSaleId) {
            return $query->where('flash_sale_id', $flashSaleId);
        }, null, $isPaginate);

        // $query = $this->model->join('products', "{$this->model->table}.product_id", '=', 'products.id')
        //     ->join('skus', 'products.id', '=', 'skus.product_id')
        //     // ->join('flash_sales', 'flash_sales.id', '=', "{$this->model->table}.flash_sale_id")
        //     ->select(
        //         "flash_sale_id",
        //         'product_name',
        //         // 'cover_image',
        //         'shipping_point',
        //         'category_id',
        //         'brand_id',
        //         'products.id',
        //         "{$type}_price as price",
        //         DB::raw("LEAST({$type}_discount + {$this->model->table}.discount,1) as discount"),
        //         'product_part_number',
        //         'products.average_rating',
        //         'products.total_rating',
        //         'products.created_at',
        //         'products.total_quantity_sold',
        //         'skus.id as sku_id'
        //     )
        //     ->where('flash_sale_id', $flashSaleId)
        //     ->where('skus.default', true);
        // if ($isPaginate) {
        //     return $query->paginate(ProductFlashSaleService::PER_PAGE_FLASH_SALE);
        // } else {
        //     return $query->get();
        // }
    }

    protected function getSelectColumns()
    {
        return [
            'product_name',
            'cover_image',
            'shipping_point',
            'category_id',
            'brand_id',
            "{$this->model->table}.id",
            "{$this->memberType}_price as price",
            "{$this->memberType}_discount as discount",
            'product_part_number'
        ];
    }
    /**
     * Summary of getProductDetails
     * @param mixed $id
     * @return void
     */
    public function getProductDetails($id)
    {
        return $this->queryJoinSkuDefault(null, [
            "{$this->model->table}.cover_image",
            "{$this->model->table}.describe",
            "{$this->model->table}.detail",
        ])->where("{$this->model->table}.id", $id)->with(['productAttributes' => function ($query) {
            $query->with(['attribute', 'productAttributeOptions']);
        }, 'productFlashSaleActive', 'productImages'])->first();

        // //truy vấn này chỉ lấy giá mặt định
        // return $this->model->join('skus as s', "{$this->model->table}.id", '=', 's.product_id')
        //     ->select(
        //         "{$this->model->table}.id",
        //         "{$this->model->table}.product_name",
        //         "{$this->model->table}.cover_image",
        //         "{$this->model->table}.shipping_point",
        //         "{$this->model->table}.category_id",
        //         "{$this->model->table}.brand_id",
        //         "{$this->model->table}.average_rating",
        //         "{$this->model->table}.total_rating",
        //         "{$this->memberType}_price as price",
        //         "{$this->memberType}_discount as discount",
        //         // DB::raw("SUM(od.quantity) as total_purchases"),
        //     )
        //     ->where('s.default', true)
        //     ->where("{$this->model->table}.id", $id)->with(['productAttributes' => function ($query) {
        //         $query->join('attributes as a', 'a.id', '=', 'product_attributes.attribute_id')
        //             ->with(['productAttributeOptions']);
        //     }, 'productImages'])->first();
    }
    public function findProductBySkuId($skuId)
    {
        return $this->model->join('skus', "{$this->model->table}.id", '=', 'skus.id')
            ->where('skus.id', $skuId)->first();
    }
    public function getRecursiveProductsFilterPaginate(Category $category = null, bool $sale = false, bool $new = false, string $sort = 'hot', bool $priceRange = false, int $priceMin = 0, int $priceMax = null, int $perPage = 10)
    {
        $discountNew = "LEAST(
                        CASE 
                            WHEN flash_sales.end_time IS NOT NULL 
                                THEN product_flash_sales.discount 
                            ELSE 0 
                        END + IFNULL(skus.{$this->memberType}_discount, 0),1) ";
        $select = [
            DB::raw("{$discountNew} AS discount_new"),
            DB::raw("skus.{$this->memberType}_price - (skus.{$this->memberType}_price * {$discountNew}) as price_new")
        ];
        if (isset($category)) {
            $query = $category->recursiveProducts(function ($query) use ($select) {
                return $this->buildQueryDataProduct($this->getQueryModelJoin($query), null, array_merge($this->getSelectDataProduct(), $select));
            });
        } else {
            $query = $this->queryDataProduct(null, $select);
        }
        $columnSort = null;
        $typeSort = null;
        if ($new == true) {
            // $query = $query->where('products.created_at', '>', Carbon::now()->subDays(10));
            $query = $query->whereIsNew();
            // $query = $query->orderBy('products.created_at', 'DESC');
        }
        $query = $query->where('skus.default', true)->groupBy('products.id');
        if ($sale == true) {
            $query = $query->having('discount_new', '>', 0);
        }
        if ($priceRange == true) {
            $query = $query->having('price_new', '>=', $priceMin);
            if ($priceMax != null) {
                $query = $query->having('price_new', '<=', $priceMax);
            }
        }
        if (isset($sort)) {
            // return $sort;
            switch ($sort) {
                case 'hot':
                    $columnSort = 'total_quantity_sold';
                    $typeSort = 'DESC';
                    break;
                case 'rating':
                    $columnSort = 'average_rating';
                    $typeSort = 'DESC';
                    break;
                case 'az':
                    $columnSort = 'product_name';
                    $typeSort = 'ASC';
                    break;
                case 'za':
                    $columnSort = 'product_name';
                    $typeSort = 'DESC';
                    break;
                case 'p-asc':
                    $columnSort = 'price_new';
                    $typeSort = 'ASC';
                    break;
                case 'p-desc':
                    $columnSort = 'price_new';
                    $typeSort = 'DESC';
                    break;
                default:
                    # code...
                    break;
            }
            $query = $query->orderBy($columnSort, $typeSort);
        }
        // $query = $query->select($select);
        // return $query->toSql();
        return $query->paginate($perPage);
        // return $category->setRelation(
        //     'recursiveProducts',
        //     $category->recursiveProducts()->paginate($perPage)
        // );


        // return $this->model->where('id',$categoryId)->with(['recursiveProducts'=>function($query) use ($perPage){
        //     $query->paginate($perPage);
        // }])->first();
    }
    public function whereInBy(string $filed, array $values)
    {
        return $this->queryJoinSkuDefault()->whereIn("products.{$filed}", $values)->get();
    }
    public function getByIdsAndSortByScore(array $productIdScores)
    {
        $productIds = [];
        $cases = implode(' ', array_map(function ($idScore) use (&$productIds) {
            array_push($productIds, $idScore['id']);
            return "WHEN {$idScore['id']} THEN {$idScore['score']}";
        }, $productIdScores));
        return $this->queryJoinSkuDefault()->whereIn("products.id", $productIds)
            ->orderByRaw("CASE products.id {$cases} ELSE 0 END DESC")->get();
    }
    public function getAllWithRelationship($page = null, $perPage = null)
    {
        $query = $this->model->with(['sellers', 'productFlashSaleActive', 'skus', 'specifications']);
        if (isset($perPage) && isset($page)) {
            $skip = $perPage * ($page - 1);
            return [
                'data' => $query->skip($skip)->take($perPage)->get()
            ];
        }
        return $query->get();
    }
}
