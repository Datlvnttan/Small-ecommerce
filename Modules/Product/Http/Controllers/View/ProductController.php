<?php

namespace Modules\Product\Http\Controllers\View;

use App\Helpers\Call;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ProductController extends Controller
{
    // protected $productElasticService;
    // protected $productService;
    // public function __construct($productElasticService, $productService)
    // {
    //     $this->productElasticService = $productElasticService;
    //     $this->productService = $productService;
    // }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('product::index');
    }

    /**
     * View product details
     * @param int $id
     * @return Renderable
     */
    public function show(int $id)
    {
        return Call::SafeExecute(function () use ($id) {
            if (is_int($id)) {
                return view('product::show')->with('id', $id);
            }
            throw new \Exception('Unable to view product');
        });
    }
    public function search(Request $request)
    {
        return Call::SafeExecute(function () use ($request) {
            return view('product::search');
        });
    }
}
