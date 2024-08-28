<?php

namespace Modules\Elastic\Http\Controllers\Api;

use App\Helpers\Call;
use App\Helpers\ResponseJson;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use League\Fractal\Resource\Collection;
use Modules\Elastic\Services\ElasticService;
use Modules\Elastic\Services\ProductElasticService;
use Modules\Elastic\Transformers\SuggestTransformer;

class ElasticsearchApiController extends Controller
{
    protected $productElasticService;
    protected $categoryElasticService;
    protected $brandElasticService;
    protected $productImageElasticService;
    protected $skuElasticService;
    protected $elasticService;

    public function __construct(
        ProductElasticService $productElasticService,
        ElasticService $elasticService,
        )
    {
        $this->productElasticService = $productElasticService;
        $this->elasticService = $elasticService;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return Call::TryCatchResponseJson(function(){
            $data = $this->productElasticService->syncDatabaseToElasticsearch();
            if(isset($data))
            {
                return ResponseJson::success(data:$data);
            }
            return ResponseJson::failed('Failed to sync');
        });
    }
    public function suggest(Request $request)
    {
        return Call::TryCatchResponseJsonFractalManager(function ($fractal) use ($request) {
            $q = $request->input('q');
            $data = $this->elasticService->suggest($q);
            $result = [];
            foreach ($data as $key => $value) {
                // $result[$key] = $value;
                $resource = new Collection($value, new SuggestTransformer());
                $result[$key] = $fractal->createData($resource)->toArray()['data'];
            }
            return ResponseJson::success(data: $result);
        });
    }
    
}
