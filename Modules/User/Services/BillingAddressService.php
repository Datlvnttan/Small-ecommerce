<?php

namespace Modules\User\Services;

use Modules\User\Repositories\Interface\BillingAddressRepositoryInterface;
use Modules\User\Repositories\Repository\BillingAddressRepository;
use Modules\User\Repositories\Repository\DeliveryAddressRepository;
use Str;


class BillingAddressService extends AddressService
{
    public function __construct(BillingAddressRepositoryInterface $billingAddressRepositoryInterface)
    {
        parent::__construct($billingAddressRepositoryInterface);
    }
    protected function getFiledMapping()
    {
        return [
            'fullname'=>'billingFullname',
            'country_id'=>'countryBillingAddressId',
            'province'=>'billingProvince',
            'district'=>'billingDistrict',
            'ward'=>'billingWard',
            'zip_code'=>'billingZipCode',
            'address_specific'=>'billingAddressSpecific',
            'tax_id_number'=>'billingTaxIDNumber',
            'default'=>'billingDefault',
        ];
    }

    // public function getByUserId($userId)
    // {
    //     return $this->addressRepositoryInterface->getByUserId($userId);
    // }
}
