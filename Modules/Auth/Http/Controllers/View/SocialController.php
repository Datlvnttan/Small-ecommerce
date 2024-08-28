<?php

namespace Modules\Auth\Http\Controllers\View;

use App\Helpers\Call;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Auth\Services\AuthService;
use Modules\Auth\Services\SocialService;
use Modules\Cart\Services\CartService;
use Tymon\JWTAuth\Facades\JWTAuth;

class SocialController extends Controller
{
    protected $socialService;
    protected $cartService;
    public function __construct(SocialService $socialService, CartService $cartService)
    {
        $this->socialService = $socialService;
        $this->cartService = $cartService;
    }
    public function loginCallback($drive, Request $request)
    {
        return Call::SafeExecute(function () use ($drive, $request) {
            $token = $this->socialService->loginCallback($drive, $request);
            $this->cartService->syncSessionCartWithDatabase(auth()->id());
            $url = AuthService::pullUrlIntended();
            // dd($url);
            return redirect($url)->withCookie("token", $token, JWTAuth::factory()->getTTL());
        });
    }
}
