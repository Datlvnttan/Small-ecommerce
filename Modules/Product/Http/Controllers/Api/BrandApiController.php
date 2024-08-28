<?php

namespace Modules\Product\Http\Controllers\Api;

use App\Helpers\Call;
use App\Helpers\ResponseJson;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Product\Services\BrandService;

class BrandApiController extends Controller
{
    protected $brandService;
    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        return Call::TryCatchResponseJson(function () use ($request) {
            $tag = $request->tag;
            if (!isset($tag)) {
                $brands = $this->brandService->all();
            } else
                $brands = $this->brandService->getHotBrands();
            return ResponseJson::success(data: $brands);
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
