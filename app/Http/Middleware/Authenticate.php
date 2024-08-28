<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Modules\Auth\Services\AuthService;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        $user = Auth::user();
        if(isset($user))
        {
            if(!isset($user->email_verified_at))
                return route('view.verify-account',["id"=>$user->id]);
            return null;
        }
        $urlCurrent = url()->current();
        AuthService::putUrlIntended($urlCurrent);
        // if($urlCurrent === route('login') || $urlCurrent === route('register'))
        //     $urlCurrent = route('web.home.index');
        // Session::put(config("app.URL_INTENDED",'url_intended'), $urlCurrent);  
        return route('login');
    }
}
