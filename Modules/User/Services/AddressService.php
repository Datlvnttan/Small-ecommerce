<?php

namespace Modules\User\Services;

use Modules\User\Entities\Address;
use Modules\User\Repositories\AddressRepositoryInterface;

abstract class AddressService
{
    protected $fieldMapping;
    protected $addressRepositoryInterface;
    public function __construct(AddressRepositoryInterface $addressRepositoryInterface)
    {
        $this->addressRepositoryInterface = $addressRepositoryInterface;
        $this->fieldMapping = $this->getFiledMapping();
    }
    abstract protected function getFiledMapping();
    public function getByUserId($userId)
    {
        return $this->addressRepositoryInterface->getByUserId($userId);
    }
    public function setDefaultsForOthers($userId, $default, $exceptAddressId)
    {
        return $this->addressRepositoryInterface->setDefaultsForOthers($userId, $exceptAddressId, $default);
    }
    public function setFirstAddressAsDefaultOfUserForOthers($userId, $exceptAddressId)
    {
        return $this->addressRepositoryInterface->setFirstAddressAsDefaultForOthers($userId, $exceptAddressId);
    }
    public function setFirstAddressAsDefaultOfUser($userId)
    {
        return $this->addressRepositoryInterface->setFirstAddressAsDefaultOfUser($userId);
    }
    public function setAddressAsDefaultOfUser(int $id,bool $default, int $userId)
    {
        $address = $this->addressRepositoryInterface->findByAddressIdOfUser($id,$userId);
        if (!$address) {
            return false;
        }
        $address->default = $default;
        $address->save();
        return true;
        // return $this->addressRepositoryInterface->setAddressAsDefaultOfUser($id,$default,$userId);
    }
    public function create(int $userId, array $data)
    {
        $newAddress = [];
        foreach ($this->fieldMapping as $field => $alias) {
            if (isset($data[$alias])) {
                $newAddress[$field] = $data[$alias];
            }
        }
        $newAddress['user_id'] = $userId;
        return $this->addressRepositoryInterface->create($newAddress);
    }
    public function update(int $userId, int $id, array $data):bool
    {
        $address = $this->addressRepositoryInterface->findByAddressIdOfUser($id,$userId);
        if (!$address) {
            return false;
        }
        foreach ($this->fieldMapping as $field => $alias) {
            if (isset($data[$alias])) {
                $address->$field = $data[$alias];
            }
            else
            {
                $address->$field = null; // set null for fields not present in data array
            }
        }
        $address->save();
        return true;
    }
    public function delete(int $userId, int $id): bool
    {
        $address = $this->addressRepositoryInterface->findByAddressIdOfUser($id,$userId);
        if (!$address) {
            return false;
        }
        $address->delete();
        return true;
    }
    public function getQuantityAddressOfUser($userId): int
    {
        return $this->addressRepositoryInterface->getQuantityAddressOfUser($userId);
    }
}
