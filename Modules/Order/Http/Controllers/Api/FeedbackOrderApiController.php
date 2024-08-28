<?php

namespace Modules\Order\Http\Controllers\Api;

use App\Helpers\Call;
use App\Helpers\ResponseJson;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Order\Http\Requests\FeedbackRequest;
use Modules\Order\Services\FeedbackOrderService;

class FeedbackOrderApiController extends Controller
{

    protected $feedbackService;
    public function __construct(FeedbackOrderService $feedbackService)
    {
        $this->feedbackService = $feedbackService;
    }
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(string $orderId, string $skuId, FeedbackRequest $request)
    {
        return Call::TryCatchResponseJson(function () use ($request, $orderId, $skuId) {
            $feedbackRating = $request->input('feedbackRating');
            $feedbackTitle = $request->input('feedbackTitle');
            $feedbackReview = $request->input('feedbackReview');
            $feedbackIncognito = $request->input('feedbackIncognito');
            $feedbackImage = null;
            // return $request->all();
            if ($request->hasFile('feedbackImage')) {
                $feedbackImage = $request->file('feedbackImage');
            }
            $success = $this->feedbackService->createFeedback($orderId, $skuId, $feedbackRating, $feedbackTitle, $feedbackReview, $feedbackImage, $feedbackIncognito);
            if ($success) {
                return ResponseJson::success('Submit Feedback Successfully');
            }
            return ResponseJson::failed('Failed to submit Feedback');
        });
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(string $orderId, string $skuId, FeedbackRequest $request)
    {
        // return $request->all();
        return Call::TryCatchResponseJson(function () use ($request, $orderId, $skuId) {
            $feedbackRating = $request->input('feedbackRating');
            $feedbackTitle = $request->input('feedbackTitle');
            $feedbackReview = $request->input('feedbackReview');
            $feedbackIncognito = $request->input('feedbackIncognito') == "on";
            $feedbackImageOld = $request->input('feedbackImageOld');
            $feedbackImageNew = null;
            if ($request->hasFile('feedbackImage')) {
                $feedbackImageNew = $request->file('feedbackImage');
            }
            $success = $this->feedbackService->updateFeedback($orderId, $skuId, $feedbackRating, $feedbackTitle, $feedbackReview, $feedbackImageOld, $feedbackImageNew, $feedbackIncognito);
            if ($success) {
                return ResponseJson::success('Submit Feedback Successfully');
            }
            return ResponseJson::failed('Failed to update Feedback');
        });
    }
    public function destroy(string $orderId, string $skuId)
    {
        // return $request->all();
        return Call::TryCatchResponseJson(function () use ( $orderId, $skuId) {
            $success = $this->feedbackService->deleteFeedback($orderId, $skuId);
            if ($success) {
                return ResponseJson::success('Remove Feedback Successfully');
            }
            return ResponseJson::failed('Failed to remove Feedback');
        });
    }
}

