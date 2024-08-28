<?php

namespace Modules\Auth\Http\Controllers\Api;

use App\Helpers\Call;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Auth\Services\SocialService;

class SocialApiController extends Controller
{
    protected $socialService;
    public function __construct(SocialService $socialService)
    {
        $this->socialService = $socialService;
    }
    public function getsocialSignInUrl($drive)
    {
        return Call::TryCatchResponseJson(function() use($drive){
            return $this->socialService->getsocialSignInUrl($drive);
        });
    }
}
