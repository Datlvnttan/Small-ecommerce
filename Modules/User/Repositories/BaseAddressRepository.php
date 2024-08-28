<?php

namespace Modules\User\Repositories;

use App\Repositories\EloquentRepository;
use Modules\User\Repositories\AddressRepositoryInterface;

abstract class BaseAddressRepository extends EloquentRepository implements AddressRepositoryInterface
{
    public function getByUserId($userId)
    {
        return $this->model->with('country')->where('user_id', $userId)->get();
    }

    public function setDefaultsForOthers(int $userId, int $exceptAddressId, int $default)
    {
        return $this->model->where('user_id', $userId)
            ->where('id', '<>', $exceptAddressId)
            ->where('default', !$default)
            ->update(['default' => $default]);
    }
    public function setFirstAddressAsDefaultForOthers(int $userId, int $exceptAddressId)
    {
        $address = $this->model->where('user_id', $userId)
            ->where('id', '<>', $exceptAddressId)
            ->first();
        if(isset($address))
        {
            $address->update(['default' => true]);
        }
    }
    public function setFirstAddressAsDefaultOfUser(int $userId)
    {
        $address = $this->model->where('user_id', $userId)
            ->where('default', false)
            ->first();
        if(isset($address))
        {
            $address->update(['default' => true]);
        }
    }
    public function findByAddressIdOfUser(int $addressId, int $userId)
    {
        return $this->model->where('id', $addressId)->where('user_id', $userId)->first();
    }
    public function deleteByAddressIdOfUser(int $addressId, int $userId)
    {
        return $this->model->where('id', $addressId)->where('user_id', $userId)->delete();
    }
    public function setAddressAsDefaultOfUser(int $addressId, int $default, int $userId)
    {
        $address = $this->model->where('id', $addressId)->where('user_id', $userId)->first();
        if(isset($address))
        {
            $address->update(['default' => $default]);
            return true;
        }
        return false;
        
    }
    public function getQuantityAddressOfUser(int $userId): int
    {
        return $this->model->where('user_id', $userId)->count();
    }
}
