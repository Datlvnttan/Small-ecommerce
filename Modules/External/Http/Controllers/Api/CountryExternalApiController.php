<?php

namespace Modules\External\Http\Controllers\Api;

use App\Helpers\Call;
use App\Helpers\ResponseJson;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\External\Services\CountryStateCityExternalApiService;

class CountryExternalApiController extends Controller
{
    protected $countryStateCityExternalApiService;

    public function __construct(CountryStateCityExternalApiService $countryStateCityExternalApiService)
    {
        $this->countryStateCityExternalApiService = $countryStateCityExternalApiService;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return Call::TryCatchResponseJson(function(){
            $countries = $this->countryStateCityExternalApiService->getCountries();
            return ResponseJson::success(data:$countries);
        });
    }
}
