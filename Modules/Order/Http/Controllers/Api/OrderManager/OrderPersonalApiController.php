<?php

namespace Modules\Order\Http\Controllers\Api\OrderManager;

use App\Helpers\Call;
use App\Helpers\ResponseJson;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Services\Manager\OrderPersonalService;
use Modules\Order\Transformers\OrderHistoryTransformer;
use Modules\Order\Transformers\OrderTransformer;

class OrderPersonalApiController extends Controller
{
    protected $orderPersonalService;
    public function __construct(OrderPersonalService $orderPersonalService)
    {
        $this->orderPersonalService = $orderPersonalService;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        return Call::TryCatchResponseJsonFractalManager(function ($fractal) use ($request) {
            $status = $request->input('status', 'all');
            $user = Auth::user();
            $orders = $this->orderPersonalService->getAllPersonalOrderHistories($user->id, $status);
            $resource = new Collection($orders, new OrderTransformer());
            $ordersTransformer = $fractal->createData($resource)->toArray();
            return ResponseJson::success(data: [
                'current_page' => $orders->currentPage(),
                'data' => $ordersTransformer['data'],
                'last_page' => $orders->lastPage(),
            ]);
        });
    }
}
