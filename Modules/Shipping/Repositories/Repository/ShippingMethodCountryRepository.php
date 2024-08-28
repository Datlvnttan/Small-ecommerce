<?php

namespace Modules\Shipping\Repositories\Repository;

use App\Repositories\EloquentCompoundPrimaryKeyRepository;
use App\Repositories\EloquentRepository;
use Modules\Shipping\Repositories\Interface\ShippingMethodCountryRepositoryInterface;

class ShippingMethodCountryRepository extends EloquentCompoundPrimaryKeyRepository implements ShippingMethodCountryRepositoryInterface
{
    public function getModel()
    {
        return \Modules\Shipping\Entities\ShippingMethodCountry::class;
    }
}
