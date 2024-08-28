<?php

namespace Modules\Auth\Services;

use App\Helpers\Helper;
use App\Helpers\ResponseJson;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Modules\Mailer\Services\MailService;
use Modules\User\Repositories\Repository\UserRepository;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;

class AuthService
{
    protected $userRepo;
    protected $mailService;
    public const URL_INTENDED = 'URL_INTENDED';//config("app.URL_INTENDED",'url_intended');
    public function __construct(UserRepository $userRepo, MailService $mailService)
    {
        $this->userRepo = $userRepo;
        $this->mailService = $mailService;
    }
    public static function pullUrlIntended()
    {
        return Session::pull(AuthService::URL_INTENDED) ?? route('web.home.index');
    }
    public static function putUrlIntended($url)
    {
        if($url === route('login') || $url === route('register'))
            $url = route('web.home.index');
        Session::put(AuthService::URL_INTENDED, $url); 
    }
     /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithToken($token)
    {
        $response = new Response($this->getResponseToken($token));
        //$response->withCookie(cookie(config("app.TOKEN_AUTH","token_auth"), $token, auth()->factory()->getTTL()*60));
        return $response->cookie("token",$token,JWTAuth::factory()->getTTL());
    }
    public function getResponseToken($token)
    {
        $user = Auth::user();
        $url = null;
        if (!isset($user->email_verified_at))
            {
                // $otp = str(rand(100000, 999999));
                $otp = Helper::randomOTPNumeric(6);
                $this->userRepo->update($user->id,[
                    "OTP"=>$otp,
                    "otp_renew_at"=>Carbon::now()
                ]);
                $this->mailService->sendEmailVerify($user->email,$user->fullname,$otp);
                $url = route('view.verify-account',["id"=>$user->id]);
            }
        else
            $url = AuthService::pullUrlIntended();
        return [
            "success"=>true,
            "data"=>[
                'url' => $url,  
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL()
                ]
            ];
    }
    public function register($data)
    {
        $user = $this->userRepo->findBy("email",$data["email"]);
        if(isset($user))
        return response()->json([
            "errors"=>[
                "email"=>["Email already exists!"]
            ]
        ],422);
        $otp = str(rand(100000, 999999));
        $data["nickname"] = Str::random(6);
        $data["OTP"] = $otp;
        $data["otp_renew_at"] = Carbon::now();
        $user = $this->userRepo->create($data);
        $this->mailService->sendEmailVerify($data["email"],$data["fullname"],$otp);
        return ResponseJson::success(data:[
            "url" => route("view.verify-account",["id" => $user->id])
        ]);
    }
    public function attemptLogin($email,$password)
    {
        if(($token = Auth::attempt(["email"=>$email,"password"=>$password])))
        {
            return $token;
        }
        return false;
    }
}
