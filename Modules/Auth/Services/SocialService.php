<?php

namespace Modules\Auth\Services;

use App\Helpers\ResponseJson;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Modules\User\Entities\User;
use Modules\User\Repositories\Repository\UserRepository;
use Illuminate\Support\Str;
use Laravel\Socialite\Two\GoogleProvider;
use Tymon\JWTAuth\Facades\JWTAuth;

class SocialService
{
    protected $userRepo;
    protected $authService;
    public function __construct(UserRepository $userRepo, AuthService $authService)
    {
        $this->userRepo = $userRepo;
        $this->authService = $authService;


        // if(isset($drive))
        // {
        //     Socialite::driver($drive)->set
        // }

    }

    public function getsocialSignInUrl($drive)
    {
        // return $drive;
        if (isset($drive)) {
            // $driveUpper = Str::upper($drive);
            // $driveUcfirst = Str::studly($drive);
            // $providerClass = "{$driveUcfirst}Provider";
            // if (class_exists($providerClass)) {
                
            //     $provider = app()->make($providerClass);
            //     // return $provider;
            //     Socialite::buildProvider($provider, [
            //         'client_id' => env("{$driveUpper}_CLIENT_ID"),
            //         'client_secret' => env("{$driveUpper}_CLIENT_SECRET"),
            //         'redirect' => route('web.social.loginCallback', ['drive' => $drive]),
            //     ]);
            // }
            $url = Socialite::driver($drive)->stateless()->redirect()->getTargetUrl();
            return ResponseJson::success(data: [
                'url' => $url
            ]);
        }
        return ResponseJson::failed('Invalid drive');
    }

    public function loginCallback($drive, Request $request)
    {
        $state = $request->input('state');
        parse_str($state, $result);
        $socialUser = Socialite::driver($drive)->stateless()->user();
        $user = $this->userRepo->findBy('provider_id', $socialUser->id);
        if (!isset($user)) {
            $user2 = $this->userRepo->findBy('email', $socialUser->email);
            if (isset($user2)) {
                $user2->provider_id = $socialUser->id;
                $user2->save();
                $user = $user2;
            } else {
                $nickname = Str::random(6);
                $user = $this->userRepo->create([
                    'email' => $socialUser->email,
                    'fullname' => $socialUser->name,
                    'nickname' => $nickname,
                    'provider_id' => $socialUser->id,
                    'email_verified_at' => Carbon::now()
                    // 'password'=> '123',
                ]);
            }
        }
        $token = Auth::login($user);
        return $token;
    }
}
