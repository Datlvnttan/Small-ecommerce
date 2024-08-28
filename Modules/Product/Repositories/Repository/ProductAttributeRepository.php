<?php

namespace Modules\Product\Repositories\Repository;

use App\Repositories\EloquentRepository;
use Modules\Product\Repositories\Interface\ProductAttributeRepositoryInterface;

class ProductAttributeRepository extends EloquentRepository implements ProductAttributeRepositoryInterface
{
    public function getModel()
    {
        return \Modules\Product\Entities\ProductAttribute::class;
    }
}
