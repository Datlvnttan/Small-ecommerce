<?php

namespace Modules\User\Http\Controllers\View;

use App\Helpers\Call;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Modules\External\Services\GeoapifyExternalApiService;
use Modules\User\Http\Requests\ChangeEmailUserRequest;
use Modules\User\Services\UserService;

class UserController extends Controller
{

    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // dd(auth()->id());
        // dd((new GeoapifyExternalApiService())->getIPlocation()['country']);
        // dd(Cookie::get());
        return view('user::index');
    }

    public function profile()
    {
        return view('user::profile');
    }
    public function VerifyChangeEmail(ChangeEmailUserRequest $request)
    {
        return Call::SafeExecute(function () use ($request) {

            $oldEmail = $request->input('oldEmail');
            $tokenChangeEmail = $request->input('tokenChangeEmail');
            if (Auth::check()) {
                $user = Auth::user();
                if ($user->email == $oldEmail) {
                    $success = $this->userService->confirmVerifyChangeEmail($tokenChangeEmail, $user);
                    if ($success) {
                        return redirect()->route('web.operation.success', [
                            'title' => 'Success',
                            'message' => 'Change email successfully'
                        ]);
                    } else {
                        throw new \Exception('Token change email is invalid');
                    }
                }
            }
            return view('auth::password-entry-form', [
                'email' => $oldEmail,
                'data' => [
                    'tokenChangeEmail' => $tokenChangeEmail,
                    'routeSubmit' => 'api.personal.profile.verifyChangeEmail',
                ],
                'jsPath' => 'Modules/user/js/change-email-user.js',
                // 'routeSubmit' => 'api.personal.profile.verifyChangeEmail'
            ]);
        });
    }
}
