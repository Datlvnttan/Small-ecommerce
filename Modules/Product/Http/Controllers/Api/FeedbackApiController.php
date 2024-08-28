<?php

namespace Modules\Product\Http\Controllers\Api;

use App\Helpers\Call;
use App\Helpers\ResponseJson;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use League\Fractal\Resource\Collection;
use Modules\Product\Services\FeedbackService;
use Modules\Product\Transformers\FeedbackTransformer;

class FeedbackApiController extends Controller
{
    protected $feedBackService;
    public function __construct(FeedbackService $feedbackService)
    {
        $this->feedBackService = $feedbackService;
    }
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Response
     */
    public function index($productId)
    {
        return Call::TryCatchResponseJsonFractalManager(function($fractal) use ($productId){
            // $productId = $request->productId;
            $feedbacks = $this->feedBackService->getFeedbackByProductId($productId);
            $resource = new Collection($feedbacks, new FeedbackTransformer());
            $feedbacksTransformer = $fractal->createData($resource)->toArray();
            return ResponseJson::success(data: [
                'data'=>$feedbacksTransformer['data'],
                'current_page'=>$feedbacks->currentPage(),
                'last_page'=>$feedbacks->lastPage(),
                'per_page'=>$feedbacks->perPage(),
            ]);
        });
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
