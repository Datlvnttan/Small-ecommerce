<?php

namespace Modules\Shipping\Repositories\Repository;

use App\Repositories\EloquentRepository;
use Modules\Shipping\Repositories\Interface\ShippingMethodRepositoryInterface;

class ShippingMethodRepository extends EloquentRepository implements ShippingMethodRepositoryInterface
{
    public function getModel()
    {
        return \Modules\Shipping\Entities\ShippingMethod::class;
    }
    public function getExpenseByCountryId($countryId)
    {
        return $this->model->join('shipping_method_countries', "{$this->model->table}.id", '=', 'shipping_method_countries.shipping_method_id')
            ->where('shipping_method_countries.country_id', $countryId)->get();
    }
}
