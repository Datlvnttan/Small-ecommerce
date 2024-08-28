<?php

namespace Modules\Order\Http\Controllers\Api;

use App\Helpers\Call;
use App\Helpers\ResponseJson;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use League\Fractal\Resource\Item;
use Modules\Order\Http\Requests\DiscountCouponRequest;
use Modules\Order\Services\DiscountCouponService;
use Modules\Order\Transformers\DiscountCouponTransformer;

class DiscountCouponApiController extends Controller
{

    protected $discountCouponService;
    public function __construct(DiscountCouponService $discountCouponService)
    {
        $this->discountCouponService = $discountCouponService;
    }
    public function getByCouponCode($couponCode)
    {
        return Call::TryCatchResponseJsonFractalManager(function($fractal) use ($couponCode){
            $discountCoupon = $this->discountCouponService->getByCouponCode($couponCode);
            if(isset($discountCoupon))
            {
                $result = new Item($discountCoupon,new DiscountCouponTransformer());
                $discountCouponTransformer =  $fractal->createData($result)->toArray();
                return ResponseJson::success(data:$discountCouponTransformer['data']);
            }
            return ResponseJson::failed('Coupon not found',Response::HTTP_NOT_FOUND);
        });
    }
}
