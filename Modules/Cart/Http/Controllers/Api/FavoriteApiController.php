<?php

namespace Modules\Cart\Http\Controllers\Api;

use App\Helpers\Call;
use App\Helpers\ResponseJson;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Cart\Services\FavoriteService;

class FavoriteApiController extends Controller
{

    protected $favoriteService;
    public function __construct(FavoriteService $favoriteService)
    {
        $this->favoriteService = $favoriteService;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        return Call::TryCatchResponseJson(function() use ($request){
            $user = Auth::user();
            if ($user)
                $favoriteItems = $this->favoriteService->getProductFavoriteByUserId($user->id,"member_{$user->member_type}");
            else
                $favoriteItems = $this->favoriteService->getSessionFavorite();
            return ResponseJson::success(data: $favoriteItems);
        });
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        return Call::TryCatchResponseJson(function () use ($request) {
            $productId = $request->productId;
            $user = Auth::user();
            $success = isset($user) ? $this->favoriteService->addProductToDBFavorite($user->id, $productId) : $this->favoriteService->addProductToSessionFavorite($productId);
            if ($success)
                return ResponseJson::success('Added product to cart successfully');
            return ResponseJson::failed('The product already exists in your favorites list');
        });
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('cart::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('cart::edit');
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
