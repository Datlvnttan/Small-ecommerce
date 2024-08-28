<?php

namespace Modules\Seller\Http\Controllers\Api;

use App\Helpers\Call;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Elastic\Services\SellerElasticService;
use Modules\Seller\Services\SellerService;

class SellerApiController extends Controller
{
    protected $sellerService;
    protected $sellerElasticService;
    public function __construct(SellerService $sellerService,SellerElasticService $sellerElasticService)
    {
        $this->sellerService = $sellerService;
        $this->sellerElasticService = $sellerElasticService;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return Call::TryCatchResponseJson(function(){
            return $this->sellerElasticService->syncDatabaseToElasticsearch();
            // return $this->sellerService->getAllWithRelationship2();
        });
    }

}
