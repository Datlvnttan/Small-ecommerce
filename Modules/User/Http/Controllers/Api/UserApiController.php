<?php

namespace Modules\User\Http\Controllers\Api;

use App\Helpers\Call;
use App\Helpers\ResponseJson;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Services\AuthService;
use Modules\User\Http\Requests\ChangeEmailUserRequest;
use Modules\User\Http\Requests\UserProfileRequest;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserApiController extends Controller
{

    protected $userService;
    protected $authService;
    public function __construct(\Modules\User\Services\UserService $userService, AuthService $authService)
    {
        $this->userService = $userService;
        $this->authService = $authService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // dd(Auth::user());
        // dd(Cookie::get());

        return response()->json([]);
    }

    /**
     * Show the specified resource.
     */
    public function profile()
    {
        return Call::TryCatchResponseJson(function () {
            $user = Auth::user();
            return ResponseJson::success(data: $user);
        });
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserProfileRequest $request)
    {
        return Call::TryCatchResponseJson(function () use ($request) {
            $user = Auth::user();
            $success = $this->userService->update($user, $request->all());
            if ($success === 1) {
                return ResponseJson::success('Successfully, we have sent your new email a link to verify the change. You will still use your old email until authentication is completed');
            }
            elseif($success === 2)
            {
                return ResponseJson::success('Update profile successfully');
            }
            return ResponseJson::failed('Update profile failed');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function changePassword(UserProfileRequest $request)
    {
        return Call::TryCatchResponseJson(function () use ($request) {
            $user = Auth::user();
            $success = $this->userService->changePassword(
                $user,
                $request->input('oldPassword'),
                $request->input('newPassword'),
            );
            if ($success) {
                return ResponseJson::success('Change password successfully');
            }
            return ResponseJson::failed('Password incorrect');
        });
    }
    public function confirmVerifyChangeEmail(ChangeEmailUserRequest $request)
    {
        return Call::TryCatchResponseJson(function () use ($request) {
            $password = $request->input('password');
            $email = $request->input('email');
            $user = $this->userService->findByEmail($email);
            if (isset($user)) {
                if (Hash::check($password, $user->password)) {
                    $tokenChangeEmail = $request->input('tokenChangeEmail');
                    $success = $this->userService->confirmVerifyChangeEmail($tokenChangeEmail, $user);
                    if ($success) {
                        if (!Auth::check()) {
                            // $tokenChangeEmail = JWTAuth::login($user);
                            // $tokenLogin = $this->authService->attemptLogin($email, $password);
                            Auth::login($user);
                        }
                        return ResponseJson::success(data:route('web.operation.success'));
                    }
                    else
                    {
                        return ResponseJson::failed('Token change email incorrect');
                    }
                } else {
                    return ResponseJson::error('Unauthorized', 401);
                }
            } else {
                return ResponseJson::error('User not found', 404);
            }
        });
    }
}
