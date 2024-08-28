<?php

namespace Modules\Order\Observers;

use Modules\Order\Entities\OrderDetail;

class FeedbackObserver
{

    protected $productService;
    public function __construct(\Modules\Product\Services\ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(OrderDetail $orderDetail): void
    {
        //
    }
    /**
     * Handle the Order "updating" event.
     */
    public function updating(OrderDetail $orderDetail)
    {
        $newFeedbackStatus = $orderDetail->feedback_status;
        $originalFeedbackStatus = $orderDetail->getOriginal('feedback_status');
        if ($newFeedbackStatus === true && $originalFeedbackStatus === false) {
            if (isset($orderDetail->feedback_rating)) {
                $this->productService->updateAggregateRatingBySkuId($orderDetail->sku_id, $orderDetail->feedback_rating);
            }
        }
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(OrderDetail $orderDetail): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(OrderDetail $orderDetail): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(OrderDetail $orderDetail): void
    {
        //
    }
}
