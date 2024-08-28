<?php

namespace Modules\Cart\Repositories\Repository;

use App\Repositories\EloquentCompoundPrimaryKeyRepository;
use App\Repositories\EloquentRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Cart\Repositories\Interface\FavoriteRepositoryInterface;

class FavoriteRepository extends EloquentCompoundPrimaryKeyRepository implements FavoriteRepositoryInterface
{
    public function getModel()
    {
        return \Modules\Cart\Entities\Favorite::class;
    }
    public function getProductFavoriteByUserId($userId, $member_type = "guest")
    {
        return $query =  $this->model
            ->join('products', 'products.id', '=', "{$this->model->table}.product_id")
            ->join('skus', 'products.id', '=', 'skus.product_id')
            ->select(
                'products.id',
                'products.product_name',
                'products.cover_image',
                'products.shipping_point',
                'products.category_id',
                'products.brand_id',
                'products.average_rating',
                'products.total_rating',
                'products.created_at',
                DB::raw("AVG(skus.{$member_type}_price) as price"),
                DB::raw("MIN(skus.{$member_type}_price) as min_price"),
                DB::raw("MAX(skus.{$member_type}_price) as max_price"),
                DB::raw("MAX(skus.{$member_type}_discount) as discount")
            )
            ->where("{$this->model->table}.user_id", $userId)
            ->groupBy(
                'products.id',
                'products.product_name',
                'products.cover_image',
                'products.shipping_point',
                'products.category_id',
                'products.brand_id',
                'products.average_rating',
                'products.total_rating',
                'products.created_at',
            )
            ->get();
        // return $this->model
        //     ->join('products as p', 'products.product_id', '=', 'p.id')
        //     ->where('products.user_id', $userId)
        //     ->select(
        //         'products.product_id',
        //         'p.product_name',
        //         'p.cover_image',
        //         'p.shipping_point',
        //         'p.average_rating',
        //         'p.total_rating',
        //         'products.sku_id',
        //         "s.{$member_type}_price',
        //         "s.{$member_type}_discount',
        //         's.quantity as sku_quantity',
        //         's.product_part_number',
        //         'products.quantity as cart_quantity"
        //     )
        //     ->get();
    }
}
