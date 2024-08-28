<?php

namespace Modules\Shipping\Http\Controllers\Api;

use App\Helpers\Call;
use App\Helpers\ResponseJson;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Shipping\Services\ShippingMethodService;

class ShippingMethodApiController extends Controller
{
    protected $shippingMethodService;
    public function __construct(ShippingMethodService $shippingMethodService)
    {
        $this->shippingMethodService = $shippingMethodService;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return Call::TryCatchResponseJson(function () {
            $shippingMethods = $this->shippingMethodService->all();
            return ResponseJson::success(data: $shippingMethods);
        });
    }
    public function show($id)
    {
        return Call::TryCatchResponseJson(function () use ($id) {
            $shippingMethod = $this->shippingMethodService->getById($id);
            return ResponseJson::success(data: $shippingMethod);
        });
    }
}
