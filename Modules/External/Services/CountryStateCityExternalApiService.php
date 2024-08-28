<?php

namespace Modules\External\Services;

use Illuminate\Support\Facades\Http;

class CountryStateCityExternalApiService extends BaseExternalApiService
{
    protected $apiKey = env('X_CSCAPI_KEY');
    protected $prefixVersion = 'v1';
    protected $prefixCountry = 'countries';
    protected $prefixState = 'states';
    protected function getBaseUrl()
    {
        return config('services.external_api.base_url_country_state_city');
    }
    protected function getHeaders()
    {
        return [
            'X-CSCAPI-KEY' => env('X_CSCAPI_KEY')
        ];
        
    }
    public function getCountries()
    {
        return $this->getData(`v1/countries`);
    }
    public function getStatesByCountryIso2($countryIso2)
    {
        return $this->getData(`{$this->prefixVersion}/{$this->prefixCountry}/{$countryIso2}/{$this->prefixState}`);
    }
    
}
