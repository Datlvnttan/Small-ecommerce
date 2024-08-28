<?php

namespace Modules\User\Observers;

use Illuminate\Support\Facades\Log;
use Modules\User\Entities\Address;
use Modules\User\Entities\User;

class AddressObserver
{

    protected $addressService;
    public function __construct($addressService)
    {
        $this->addressService = $addressService;
    }

    /**
     * Handle the Address "created" event.
     */
    public function created(Address $address)
    {
        $newDefault = $address->default;
        if ($newDefault == true) {
            $observer = Address::getEventDispatcher();
            Address::unsetEventDispatcher();
            $this->addressService->setDefaultsForOthers($address->user_id, false, $address->id);
            Address::setEventDispatcher($observer);
        }
    }
    /**
     * Handle the Address "creating" event.
     */
    public function creating(Address $address)
    {
        $newDefault = $address->default;
        if ($newDefault == false) {
            $addressCount = $this->addressService->getQuantityAddressOfUser($address->user_id);
            if ($addressCount == 0) {
                $address->default = true;
            }
        }
    }
    /**
     * Handle the Address "updating" event.
     */
    public function updating($address)
    {

        $newDefault = $address->default;
        $originalDefault = $address->getOriginal('default');
        if ($newDefault != $originalDefault) {
            $observer = Address::getEventDispatcher();
            Address::unsetEventDispatcher();
            if ($newDefault == true) {
                $this->addressService->setDefaultsForOthers($address->user_id, false, $address->id);
            } else {
                $addressCount = $this->addressService->getQuantityAddressOfUser($address->user_id);
                if ($addressCount == 1) {
                    $address->default = true;
                } else {
                    $this->addressService->setFirstAddressAsDefaultOfUserForOthers($address->user_id, $address->id);
                }
            }
            Address::setEventDispatcher($observer);
        }
    }

    /**
     * Handle the Address "deleted" event.
     */
    public function deleted(Address $address)
    {
        $newDefault = $address->default;
        if ($newDefault == true) {
            $observer = Address::getEventDispatcher();
            Address::unsetEventDispatcher();
            if ($newDefault == true) {
                $this->addressService->setFirstAddressAsDefaultOfUser($address->user_id);
            }
            Address::setEventDispatcher($observer);
        }
    }
}
