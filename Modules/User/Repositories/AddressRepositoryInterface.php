<?php

namespace Modules\User\Repositories;

use App\Repositories\RepositoryInterface;

interface AddressRepositoryInterface extends RepositoryInterface
{
    public function getByUserId($userId);

    public function setDefaultsForOthers(int $userId, int $exceptAddressId, int $default);
    public function setFirstAddressAsDefaultForOthers(int $userId, int $exceptAddressId);

    public function setFirstAddressAsDefaultOfUser(int $userId);
    public function setAddressAsDefaultOfUser(int $addressId, int $default, int $userId);
    public function findByAddressIdOfUser(int $addressId, int $userId);
    public function deleteByAddressIdOfUser(int $addressId, int $userId);
    public function getQuantityAddressOfUser(int $userId): int;
}
