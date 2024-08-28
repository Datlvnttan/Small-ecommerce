<?php

namespace Modules\Shipping\Services;

use Modules\Shipping\Repositories\Interface\CountryRepositoryInterface;

class CountryService
{
    protected $countryRepositoryInterface;
    public function __construct(CountryRepositoryInterface $countryRepositoryInterface)
    {
        $this->countryRepositoryInterface = $countryRepositoryInterface;
    }
    public function all()
    {
        return $this->countryRepositoryInterface->all();
    }
    public function getDeliveryCostsByCountry($countryId)
    {
        return $this->countryRepositoryInterface->getDeliveryCostsByCountry($countryId);
    }
    public function findByIsoCode($isoCodeDefault)
    {
        return $this->countryRepositoryInterface->findByIsoCode($isoCodeDefault);
    }
}
