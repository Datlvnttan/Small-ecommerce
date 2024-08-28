<?php

namespace Modules\Product\Services;

use App\Helpers\Helper;
use Modules\Order\Repositories\Interface\OrderDetailRepositoryInterface;
use Modules\Product\Repositories\Interface\ProductRepositoryInterface;

class FeedbackService
{    protected $orderDetailRepositoryInterface;
    protected $feedbackPerPage = 10;
    public function __construct(OrderDetailRepositoryInterface $orderDetailRepository)
    {
        $this->orderDetailRepositoryInterface = $orderDetailRepository;
    }
    public function getFeedbackByProductId($productId)
    {
        return $this->orderDetailRepositoryInterface->getFeedbackByProductId($productId,$this->feedbackPerPage);
    }
}
