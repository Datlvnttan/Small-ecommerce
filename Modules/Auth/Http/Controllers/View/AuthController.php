<?php

namespace Modules\Auth\Http\Controllers\View;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Modules\Auth\Services\AuthService;
use Modules\User\Repositories\Repository\UserRepository;
use Modules\User\Services\UserService;

class AuthController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     */
    public function login()
    {
        // dd(url()->previous());
        $url = url()->previous();
        AuthService::putUrlIntended($url);
        // if ($url != route('login') && $url != route('register'))
        //     Session::put(AuthService::URL_INTENDED, $url);
        return view('auth::login');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function register()
    {
        return view('auth::register');
    }
    public function verifyAccountEmail(string $id)
    {
        $user = $this->userService->findUserById($id);
        if (isset($user)) {
            if (isset($user->email_verified_at))
                return view("auth::error-verify-account-email", [
                    "error" => "This account has been previously verified"
                ]);
            return view('auth::verify-account-email');
        }
        return view("auth::error-verify-account-email", [
            "error" => "Account does not exist"
        ]);
    }
    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect()->route('web.home.index');
    }
}
