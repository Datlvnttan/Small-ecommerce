<?php
namespace Modules\User\Repositories\Repository;

use App\Repositories\EloquentRepository;
use App\Repositories\RepositoryInterface;
use Modules\User\Repositories\BaseAddressRepository;
use Modules\User\Repositories\Interface\BillingAddressRepositoryInterface;

class BillingAddressRepository extends BaseAddressRepository implements BillingAddressRepositoryInterface
{
    public function getModel()
    {
        return \Modules\User\Entities\BillingAddress::class;
    }
    public function getByUserId($userId)
    {
        return $this->model->with('country')->where('user_id', $userId)->get();
    }
}
