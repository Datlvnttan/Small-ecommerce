<?php

namespace Modules\Product\Http\Controllers\Api;

use App\Helpers\Call;
use App\Helpers\ResponseJson;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use League\Fractal\Resource\Collection;
use Modules\Product\Services\FlashSaleService;
use Modules\Product\Services\ProductFlashSaleService;
use Modules\Product\Services\ProductService;
use Modules\Product\Transformers\ItemProductTransformer;

class ProductFlashSaleApiController extends Controller
{
    protected $productFlashSaleService;
    protected $flashSaleService;
    protected $productService;

    public function __construct(ProductFlashSaleService $productFlashSaleService,FlashSaleService $flashSaleService, ProductService $productService)
    {
        $this->productFlashSaleService = $productFlashSaleService;
        $this->flashSaleService = $flashSaleService;
        $this->productService = $productService;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        return Call::TryCatchResponseJsonFractalManager(function ($fractal) use ($request){
            $flashSale = $this->flashSaleService->getFitFlashSale();
            if(!isset($flashSale))
                return ResponseJson::failed("No FlashSale found",Response::HTTP_NOT_FOUND);
            $productFlashSales = $this->productService->getProductFlashSaleByFlashSaleId($flashSale->id);
            $resource = new Collection($productFlashSales, new ItemProductTransformer());
            $productFlashSales = $fractal->createData($resource)->toArray();
            return ResponseJson::success(data: [
                "flashSale" => $flashSale,
                "productFlashSales" => $productFlashSales['data']
            ]);
        });
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('product::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('product::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('product::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
