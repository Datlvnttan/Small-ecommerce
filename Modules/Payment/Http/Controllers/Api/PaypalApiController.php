<?php

namespace Modules\Payment\Http\Controllers\Api;

use App\Helpers\Call;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Payment\Services\PayPalService;

class PaypalApiController extends Controller
{
    protected $paypalService;
    public function __construct(PayPalService $paypalService)
    {
        $this->paypalService = $paypalService;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return Call::TryCatchResponseJson(function(){
            return $this->paypalService->getUrlPaymentPayPal("123",435);
        });
    }
}
