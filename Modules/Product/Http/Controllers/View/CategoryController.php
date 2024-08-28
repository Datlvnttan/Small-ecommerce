<?php

namespace Modules\Product\Http\Controllers\View;

use App\Helpers\Call;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('product::index');
    }
    public function productList()
    {
        return Call::SafeExecute(function(){
            return view('product::category-product-list');
        });
    }
}
