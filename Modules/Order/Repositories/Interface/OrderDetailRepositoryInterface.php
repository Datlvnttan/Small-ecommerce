<?php
namespace Modules\Order\Repositories\Interface;

use App\Repositories\RepositoryInterface;

interface OrderDetailRepositoryInterface extends RepositoryInterface
{
    public function getFeedbackByProductId($productId,$perPage);
    public function getFeedbackOverviewByProductId($productId);
}
