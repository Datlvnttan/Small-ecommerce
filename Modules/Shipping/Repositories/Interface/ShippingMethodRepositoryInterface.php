<?php
namespace Modules\Shipping\Repositories\Interface;

use App\Repositories\RepositoryInterface;

interface ShippingMethodRepositoryInterface extends RepositoryInterface
{
    public function getExpenseByCountryId($countryId);
}
