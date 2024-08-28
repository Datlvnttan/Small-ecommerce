<?php

namespace Modules\Product\Repositories\Repository;

use App\Repositories\EloquentRepository;
use Modules\Product\Repositories\Interface\SkuProductAttributeOptionRepositoryInterface;

class SkuProductAttributeOptionRepository extends EloquentRepository implements SkuProductAttributeOptionRepositoryInterface
{
    public function getModel()
    {
        return \Modules\Product\Entities\SkuProductAttributeOption::class;
    }
}
