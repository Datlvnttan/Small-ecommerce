<?php

namespace Modules\User\Observers;

use Illuminate\Support\Facades\Log;
use Modules\User\Entities\Address;
use Modules\User\Entities\DeliveryAddress;
use Modules\User\Entities\User;

class DeliveryAddressObserver extends AddressObserver
{

    public function __construct(\Modules\User\Services\DeliveryAddressService $addressService)
    {
        parent::__construct($addressService);
    }

    // public function updating(DeliveryAddress $address)
    // {
    //     Log::info('vakdhadgashdgasdgh');
    //     $newDefault = $address->default;
    //     $originalDefault = $address->getOriginal('default');
    //     if ($newDefault != $originalDefault) {
    //         $observer = Address::getEventDispatcher();
    //         Address::unsetEventDispatcher();
    //         if ($newDefault == true) {
    //             $this->addressService->setDefaultsForOthers($address->user_id, false, $address->id);
    //         } else {
    //             $this->addressService->setFirstAddressAsDefaultOfUserForOthers($address->user_id, $address->id);
    //         }
    //         Address::setEventDispatcher($observer);
    //     }
    // }

}
