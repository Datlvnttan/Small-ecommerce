<?php
namespace Modules\User\Repositories\Repository;

use App\Repositories\EloquentRepository;
use Modules\User\Entities\User;
use Modules\User\Repositories\Interface\UserRepositoryInterface;

class UserRepository extends EloquentRepository implements UserRepositoryInterface
{
    public function getModel()
    {
        return User::class;
    }
}
