<?php

namespace Modules\Shipping\Http\Controllers\Api;

use App\Helpers\Call;
use App\Helpers\ResponseJson;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use League\Fractal\Resource\Item;
use Modules\External\Services\GeoapifyExternalApiService;
use Modules\Shipping\Services\CountryService;
use Modules\Shipping\Transformers\CountryTransformer;

class CountryApiController extends Controller
{
    protected $countryService;
    protected $geoapifyExternalApiService;

    public function __construct(CountryService $countryService, GeoapifyExternalApiService $geoapifyExternalApiService)
    {
        $this->countryService = $countryService;
        $this->geoapifyExternalApiService = $geoapifyExternalApiService;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        return Call::TryCatchResponseJson(function () use ($request) {
            // return $request->all();
            $isGetLocation = $request->isGetLocation;
            $iso_code_default = null;
            if (isset($isGetLocation) && (int)$isGetLocation === 1) {
                $iso_code_default = $this->geoapifyExternalApiService->getIPlocation()['country']['iso_code'];
            }
            $countries = $this->countryService->all();
            return ResponseJson::success(data: [
                'countries' => $countries,
                'iso_code_default' => $iso_code_default,
            ]);
        });
    }

    public function getDeliveryCostsByCountry($countryId = null)
    {
        return Call::TryCatchResponseJsonFractalManager(function ($fractal) use ($countryId) {
            if (!isset($countryId)) {
                $user = Auth::user();
                if (isset($user)) {
                    $deliveryAddressDefault = $user->deliveryAddressDefault;
                    // return $deliveryAddressDefault;
                    if (isset($deliveryAddressDefault)) {
                        $countryId = $deliveryAddressDefault->country_id;
                    }
                }
                if (!isset($countryId)) {
                    $isoCodeDefault = $this->geoapifyExternalApiService->getIPlocation()['country']['iso_code'];
                    $country = $this->countryService->findByIsoCode($isoCodeDefault);
                    if(isset($country)) {
                        $countryId = $country->id;
                    }
                    else
                    {
                        return ResponseJson::error('Your location could not be found');
                    }
                }
            }
            $expenses = $this->countryService->getDeliveryCostsByCountry($countryId);
            $result = new Item($expenses,new CountryTransformer());
            $expensesTransformer = $fractal->createData($result)->toArray();
            return ResponseJson::success(data: $expensesTransformer['data']);
        });
    }
}
