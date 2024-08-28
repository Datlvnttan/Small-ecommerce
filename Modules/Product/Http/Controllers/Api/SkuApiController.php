<?php

namespace Modules\Product\Http\Controllers\Api;

use App\Helpers\Call;
use App\Helpers\ResponseJson;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Modules\Product\Http\Requests\SkuRequest;
use Modules\Product\Services\SkuService;
use Modules\Product\Transformers\SkuTransformer;

class SkuApiController extends Controller
{
    protected $skuService;
    public function __construct(SkuService $skuService)
    {
        $this->skuService = $skuService;
    }
    public function getByOptions(SkuRequest $request)
    {
        return Call::TryCatchResponseJsonFractalManager(function ($fractal) use ($request) {
            $optionIds = $request->optionIds;
            $user = Auth::user();
            $sku = $this->skuService->getByOptions($optionIds, $user);
            if (isset($sku)) {
                $resource = new Item($sku, new SkuTransformer());
                $skuTransformer = $fractal->createData($resource)->toArray();
                return ResponseJson::success(data: $skuTransformer['data']);
            }
            else
            {
                return ResponseJson::error('SKU not found',404);
            }
        });
    }
}
