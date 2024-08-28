<?php

namespace Modules\Seller\Repositories\Repository;

use App\Repositories\EloquentRepository;
use Modules\Seller\Repositories\Interface\SellerRepositoryInterface;

class SellerRepository extends EloquentRepository implements SellerRepositoryInterface
{
    public function getModel()
    {
        return \Modules\Seller\Entities\Seller::class;
    }
    public function getAllWithRelationship($perPage = null)
    {
        $query = $this->model->with(['products' => function ($query) {
            $query->with(['productFlashSaleActive', 'skus', 'specifications']);
        }]);
        // $query = $this->model->with(['products']);
        if (isset($perPage)) {
            return $query->paginate($perPage);
        }
        return $query->get();
    }
}
