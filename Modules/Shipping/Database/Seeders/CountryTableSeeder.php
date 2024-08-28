<?php

namespace Modules\Shipping\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\External\Services\CountryStateCityExternalApiService;
use Modules\Shipping\Entities\Country;

class CountryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $service = new CountryStateCityExternalApiService();
        // $countries = $service->getCountries();
        if (Country::count() == 0) {
            $response = Http::withHeaders([
                'X-CSCAPI-KEY' => env('X_CSCAPI_KEY','czk1YW02YzJhVHkzNGJMZjhGdTNkYmhFUXlkYnByQmttVmlJbUNSQw==')
            ])->get('https://api.countrystatecity.in/v1/countries');
            if ($response->successful()) {
                $countries = $response->json();
                foreach ($countries as $country) {
                    Country::create([
                        'country_name' => $country['name'],
                        'international_calling_code' => $country['phonecode'],
                        'iso_code' => $country['iso2'],
                    ]);
                }
            }
        }
    }
}
