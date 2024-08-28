<?php
namespace Modules\User\Repositories\Repository;

use App\Repositories\RepositoryInterface;
use Modules\User\Repositories\BaseAddressRepository;
use Modules\User\Repositories\Interface\DeliveryAddressRepositoryInterface;

class DeliveryAddressRepository extends BaseAddressRepository implements DeliveryAddressRepositoryInterface
{
    public function getModel()
    {
        return \Modules\User\Entities\DeliveryAddress::class;
    }

}
