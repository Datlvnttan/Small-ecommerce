<?php

namespace Modules\User\Observers;

use Modules\User\Entities\Address;
use Modules\User\Entities\User;

class BillingAddressObserver extends AddressObserver
{
    public function __construct(\Modules\User\Services\BillingAddressService $addressService)
    {
        parent::__construct($addressService);
    }
}
