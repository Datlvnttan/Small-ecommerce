<?php

namespace Modules\Auth\Http\Controllers\Api;

use App\Helpers\Call;
use App\Helpers\ResponseJson;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Http\Requests\RegisterRequest;
use Modules\Auth\Services\AuthService;
use Modules\Cart\Services\CartService;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthApiController extends Controller
{
    protected $authService;
    protected $cartService;
    public function __construct(AuthService $authService, CartService $cartService)
    {
        $this->authService = $authService;
        $this->cartService = $cartService;
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $loginRequest)
    {
        return Call::TryCatchResponseJson(function() use ($loginRequest){
            $email = $loginRequest->input("email");
            $password = $loginRequest->input("password");
            $token = $this->authService->attemptLogin($email, $password);
            if($token === false)
            {
                return ResponseJson::error('Unauthorized',401);
            }
            $this->cartService->syncSessionCartWithDatabase(auth()->id());
            // $credentials = [
            //     'email' => $loginRequest->input("email"),
            //     'password' => $loginRequest->input("password"),
            // ];
            // if (! $token = auth()->attempt($credentials)) {
            //     return response()->json(['error' => 'Unauthorized'], 401);
            // }
            return $this->authService->respondWithToken($token);
        });
    }
    public function register(RegisterRequest $registerRequest)
    {
        return Call::TryCatchResponseJson(function() use ($registerRequest){
            return $this->authService->register($registerRequest->all());
        });
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(JWTAuth::refresh());
    }
}
