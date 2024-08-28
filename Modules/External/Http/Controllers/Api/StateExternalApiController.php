<?php

namespace Modules\External\Http\Controllers\Api;

use App\Helpers\Call;
use App\Helpers\ResponseJson;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\External\Services\CountryStateCityExternalApiService;

class StateExternalApiController extends Controller
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
    public function index($countryIso2)
    {
        return Call::TryCatchResponseJson(function() use($countryIso2){
            $states = $this->countryStateCityExternalApiService->getStatesByCountryIso2($countryIso2);
            return ResponseJson::success(data:$states);
        });
    }
}
