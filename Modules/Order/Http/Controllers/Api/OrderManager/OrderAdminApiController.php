<?php

namespace Modules\Order\Http\Controllers\Api\OrderManager;

use App\Helpers\Call;
use App\Helpers\ResponseJson;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Order\Services\Manager\OrderAdminService;

class OrderAdminApiController extends Controller
{
    protected $orderAdminService;
    public function __construct(OrderAdminService $orderAdminService)
    {
        $this->orderAdminService = $orderAdminService;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('order::index');
    }


    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('order::show');
    }

    /**
     * Update status order
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function updateNextStatus(Request $request, $id)
    {
        return Call::TryCatchResponseJson(function () use ($request, $id) {
            $status = $request->input('status');
            $success = $this->orderAdminService->updateNextStatus($id);
            if($success)
            {
                return ResponseJson::success('Order updated status successfully');
            }
            else
            {
                return ResponseJson::failed('The current order status can no longer be updated');
            }
        });
    }

}
