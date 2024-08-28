<?php

namespace Modules\Order\Http\Controllers\View\OrderManager;

use App\Helpers\Call;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Order\Enums\OrderStatus;

class OrderPersonalController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('order::order-personal.index',[
            'orderStatuses'=>array_filter(
                OrderStatus::cases(),
                fn($status) => $status !== OrderStatus::AwaitingVerification
            )
        ]);
    }


    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('order::order-personal.show');
    }
}
