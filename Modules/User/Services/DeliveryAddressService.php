<?php

namespace Modules\User\Services;

use Modules\User\Repositories\Interface\DeliveryAddressRepositoryInterface;
use Modules\User\Repositories\Repository\BillingAddressRepository;
use Str;


class DeliveryAddressService extends AddressService
{
    public function __construct(DeliveryAddressRepositoryInterface $deliveryAddressRepositoryInterface)
    {
        parent::__construct($deliveryAddressRepositoryInterface);
    }
    protected function getFiledMapping()
    {
        return [
            'fullname'=>'deliveryFullname',
            'country_id'=>'countryDeliveryAddressId',
            'province'=>'deliveryProvince',
            'district'=>'deliveryDistrict',
            'ward'=>'deliveryWard',
            'zip_code'=>'deliveryZipCode',
            'address_specific'=>'deliveryAddressSpecific',
            'international_calling_code'=>'deliveryInternationalCallingCode',
            'phone_number'=>'deliveryPhoneNumber',
            'default'=>'deliveryDefault',
        ];
    }
}
