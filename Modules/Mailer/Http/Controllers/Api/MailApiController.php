<?php

namespace Modules\Mailer\Http\Controllers\Api;

use App\Helpers\Call;
use App\Helpers\ResponseJson;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Mailer\Http\Requests\VerifyEmailRequest;
use Modules\Mailer\Services\MailService;

class MailApiController extends Controller
{
    protected $mailService;
    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }
    public function resendEmailVerify(string $id)
    {
        return Call::TryCatchResponseJson( function() use ($id){
            if ($this->mailService->resendEmailVerify($id))
                return ResponseJson::success("Successful manipulation!!!");
            return ResponseJson::error("Failed action",422);
        });

    }
    public function verify(string $id, VerifyEmailRequest $request)
    {
        return Call::TryCatchResponseJson(function() use ($id, $request){
            // return str($request->otp_code)."123";
            $status = $this->mailService->checkOTPCode($id,$request->input("otp_code"));
            switch ($status) {
                case 1:
                    return ResponseJson::success("Successful authentication",[
                        "url"=>Session::pull(config("app.URL_INTENDED",'url_intended')) ?? config('app.url_default')
                    ]);
                case 0:
                    return ResponseJson::error("OTP code is incorrect");
                case -1:
                    return ResponseJson::error("OTP has expired, please create another OTP code");
                default:
                    return ResponseJson::error("User not found");
            }
        });
    }
}
