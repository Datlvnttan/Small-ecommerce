<?php

namespace Modules\Main\Http\Controllers\View;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('main::index');
    }
    public function success()
    {
        return view('main::operations.success');
    }
}
