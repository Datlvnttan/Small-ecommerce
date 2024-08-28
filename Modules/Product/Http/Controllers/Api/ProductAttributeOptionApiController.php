<?php

namespace Modules\Product\Http\Controllers\Api;

use App\Helpers\Call;
use App\Helpers\ResponseJson;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Product\Services\ProductAttributeOptionService;

class ProductAttributeOptionApiController extends Controller
{
    protected $productAttributeOptionService;
    public function __construct(ProductAttributeOptionService $productAttributeOptionService)
    {
        $this->productAttributeOptionService = $productAttributeOptionService;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        return Call::TryCatchResponseJson(function () use ($request) {
            $productId = $request->get('productId');
            $data = $this->productAttributeOptionService->getProductAttributeOptionByProductId($productId);
            return ResponseJson::success(data:$data);
        });
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
