<?php
namespace Modules\Shipping\Repositories\Interface;

use App\Repositories\RepositoryInterface;

interface CountryRepositoryInterface extends RepositoryInterface
{
    public function getDeliveryCostsByCountry($countryId);
    public function findByInternationalCallingCode($internationalCallingCode);
    public function findByIsoCode($isoCodeDefault);
}
