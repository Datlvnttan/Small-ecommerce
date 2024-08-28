<?php

namespace Modules\Order\Repositories\Repository;

use App\Repositories\EloquentCompoundPrimaryKeyRepository;
use App\Repositories\EloquentRepository;
use Illuminate\Support\Facades\DB;
use Modules\Order\Repositories\Interface\OrderDetailRepositoryInterface;

class OrderDetailRepository extends EloquentCompoundPrimaryKeyRepository implements OrderDetailRepositoryInterface
{
    public function getModel()
    {
        return \Modules\Order\Entities\OrderDetail::class;
    }
    public function getFeedbackByProductId($productId,$perPage)
    {
        return $this->model->join('sku_product_attribute_options as so', "{$this->model->table}.sku_id", '=', 'so.sku_id')
            ->join('skus as s', 's.id', '=', "{$this->model->table}.sku_id")
            ->join('product_attribute_options as po', 'so.product_attribute_option_id', '=', 'po.id')
            ->join('orders','orders.id', '=', "{$this->model->table}.order_id")
            ->join('users','orders.user_id', '=','users.id')
            ->where('s.product_id', $productId)
            ->where("{$this->model->table}.feedback_status", true)
            ->whereNotNull("{$this->model->table}.feedback_created_at")
            ->select(
                's.product_id',
                "{$this->model->table}.sku_id",
                "{$this->model->table}.feedback_title",
                "{$this->model->table}.feedback_rating",
                "{$this->model->table}.feedback_image",
                "{$this->model->table}.feedback_review",
                "{$this->model->table}.feedback_created_at",

                "{$this->model->table}.feedback_is_updated",
                "{$this->model->table}.feedback_incognito",
                'users.nickname',
                DB::raw("GROUP_CONCAT(po.option_name ORDER BY po.id SEPARATOR ', ') AS options")
            )->groupBy(
                's.product_id', 
                "{$this->model->table}.sku_id",
                "{$this->model->table}.feedback_title",
                "{$this->model->table}.feedback_rating",
                "{$this->model->table}.feedback_image",
                "{$this->model->table}.feedback_review",
                "{$this->model->table}.feedback_created_at",
                "{$this->model->table}.feedback_is_updated",
                "{$this->model->table}.feedback_incognito",
            )->orderBy("{$this->model->table}.feedback_created_at",'DESC')
            ->paginate($perPage);
    }
    public function getFeedbackOverviewByProductId($productId)
    {
        return $this->model->join('skus','skus.id','=',"{$this->model->table}.sku_id")
        ->where('skus.product_id', $productId)
        ->whereNotNull("{$this->model->table}.feedback_created_at")
        ->select([
            "{$this->model->table}.feedback_rating as rating",
            DB::raw("count({$this->model->table}.feedback_rating) as count_rating")
        ])
        ->groupBy([
            "{$this->model->table}.feedback_rating",
        ])->orderBy("{$this->model->table}.feedback_rating",'desc')->get();
    }
}
