<?php

namespace Modules\Shipping\Services;

use Modules\Shipping\Repositories\Interface\ShippingMethodRepositoryInterface;

class ShippingMethodService
{
    protected $shippingMethodRepositoryInterface;
    public function __construct(ShippingMethodRepositoryInterface $shippingMethodRepositoryInterface)
    {
        $this->shippingMethodRepositoryInterface = $shippingMethodRepositoryInterface;
    }
    public function all()
    {
        return $this->shippingMethodRepositoryInterface->all();
    }
    public function getById($id)
    {
        return $this->shippingMethodRepositoryInterface->find($id);
    }
}
