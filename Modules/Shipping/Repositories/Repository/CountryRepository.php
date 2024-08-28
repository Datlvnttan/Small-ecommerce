<?php

namespace Modules\Shipping\Repositories\Repository;

use App\Repositories\EloquentRepository;
use Modules\Shipping\Repositories\Interface\CountryRepositoryInterface;

class CountryRepository extends EloquentRepository implements CountryRepositoryInterface
{
    public function getModel()
    {
        return \Modules\Shipping\Entities\Country::class;
    }
    public function getDeliveryCostsByCountry($countryId)
    {
        return $this->model->with('shippingMethods')->find($countryId);
    }
    public function findByInternationalCallingCode($internationalCallingCode)
    {
        return $this->model->where('international_calling_code', $internationalCallingCode)->first();
    }
    public function findByIsoCode($isoCodeDefault)
    {
        return $this->model->where('iso_code', $isoCodeDefault)->first();
    }
}
