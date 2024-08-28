<?php

namespace Modules\Order\Services;

use App\Helpers\Helper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Repositories\Interface\OrderDetailRepositoryInterface;
use Illuminate\Support\Str;


class FeedbackOrderService
{
    protected $orderDetailRepositoryInterface;
    public const FEEDBACK_IMAGE_PATH = 'modules/order/feedbacks';
    public function __construct(OrderDetailRepositoryInterface $orderDetailRepositoryInterface)
    {
        $this->orderDetailRepositoryInterface = $orderDetailRepositoryInterface;
    }

    public function createFeedback($orderId, $skuId, $feedbackRating, $feedbackTitle, $feedbackReview, $feedbackImage = null, $feedbackIncognito = null)
    {
        $orderDetail = $this->findOrderDetailByOrderIdAndSkuId($orderId, $skuId);
        if (isset($orderDetail->feedback_created_at)) {
            throw new \Exception('You have already provided feedback for this item.');
        } else {
            // return $orderDetail->order->current_status;
            if ($orderDetail->order->current_status === OrderStatus::Delivered) {
                return $this->update($orderDetail, $feedbackRating, $feedbackTitle, $feedbackReview, $feedbackImage, $feedbackIncognito);
            } else {
                throw new \Exception('This order has been delivered. You cannot provide feedback.');
            }
        }
    }

    public function updateFeedback($orderId, $skuId, $feedbackRating, $feedbackTitle, $feedbackReview, $feedbackImageOld, $feedbackImageNew = null, $feedbackIncognito = null)
    {
        $orderDetail = $this->findOrderDetailByOrderIdAndSkuId($orderId, $skuId);
        if ($orderDetail->order->current_status !== OrderStatus::Delivered) {
            throw new \Exception('You can only update your feedback after the order has been delivered.');
        } elseif (!isset($orderDetail->feedback_created_at)) {
            throw new \Exception('You have not provided feedback for this item yet.');
        } elseif ($orderDetail->feedback_status == true) {
            throw new \Exception('The feedback has been approved, you can no longer update it!!!');
        }
        return $this->update($orderDetail, $feedbackRating, $feedbackTitle, $feedbackReview, $feedbackImageNew, $feedbackIncognito, $feedbackImageOld);
    }
    public function deleteFeedback($orderId, $skuId)
    {
        $orderDetail = $this->findOrderDetailByOrderIdAndSkuId($orderId, $skuId);
        if ($orderDetail->order->current_status !== OrderStatus::Delivered) {
            throw new \Exception('You can only delete your feedback after the order has been delivered.');
        } elseif (!isset($orderDetail->feedback_created_at)) {
            throw new \Exception('You have not provided feedback for this item yet.');
        } elseif ($orderDetail->feedback_status == true) {
            throw new \Exception('The feedback has been approved, you cannot delete it anymore!!!');
        }
        return Helper::DBTransaction(function () use ($orderDetail){
            $feedbackPathImage = public_path(FeedbackOrderService::FEEDBACK_IMAGE_PATH . '/' . $orderDetail->feedback_image);
            $orderDetail->feedback_rating = null;
            $orderDetail->feedback_title = null;
            $orderDetail->feedback_review = null;
            $orderDetail->feedback_created_at = null;
            $orderDetail->feedback_image = null;        
            $orderDetail->feedback_incognito = null;        
            $orderDetail->feedback_is_updated = null;        
            $orderDetail->feedback_status = null;   
            $orderDetail->save();
            if (file_exists($feedbackPathImage)) {
                unlink($feedbackPathImage);
            }
            return true; 
        });
    }

    protected function update($orderDetail, $feedbackRating, $feedbackTitle, $feedbackReview, $feedbackImage = null, $feedbackIncognito = null, $feedbackImageOld = null)
    {
        return Helper::DBTransaction(function () use ($orderDetail, $feedbackRating, $feedbackTitle, $feedbackReview, $feedbackImage, $feedbackIncognito) {
            $orderDetail->feedback_rating = $feedbackRating;
            $orderDetail->feedback_title = $feedbackTitle;
            $orderDetail->feedback_review = $feedbackReview;
            $orderDetail->feedback_created_at = now();
            if (isset($feedbackIncognito)) {
                if ($feedbackIncognito) {
                    $orderDetail->feedback_incognito = true;
                } else {
                    $orderDetail->feedback_incognito = false;
                }
            } else {
                $orderDetail->feedback_incognito = false;
            }
            //lấy path ảnh cũ
            $feedbackPathImage = public_path(FeedbackOrderService::FEEDBACK_IMAGE_PATH . '/' . $orderDetail->feedback_image);
            //nếu có cập nhật ảnh mới
            if (isset($feedbackImage)) {
                //Lấy lại tên ảnh cũ
                $feedbackImageOld = $orderDetail->feedback_image;
                //lưu ảnh
                if ($feedbackImage->isValid()) {
                    $fileName = time() . '_' . $feedbackImage->getClientOriginalName();
                    $feedbackImage->move(public_path(FeedbackOrderService::FEEDBACK_IMAGE_PATH), $fileName);
                    $orderDetail->feedback_image = $fileName;
                }
                //Xóa ảnh cũ nếu có
                if (isset($feedbackImageOld) && file_exists($feedbackPathImage)) {
                    unlink($feedbackPathImage);
                }
                //Nếu không có ảnh mới
            } else {
                //Kiểm tra xem ảnh cũ còn hay khôg
                if (!isset($feedbackImageOld)) {
                    //Nếu có ảnh cũ mà request gửi về bị xóa
                    if (isset($orderDetail->feedback_image)) {
                        unlink($feedbackPathImage);
                    }
                    $orderDetail->feedback_image = null;
                }
            }
            $orderDetail->save();
            return true;
        });
    }
    protected function findOrderDetailByOrderIdAndSkuId($orderId, $skuId)
    {
        $orderDetail = $this->orderDetailRepositoryInterface->find([
            'order_id' => $orderId,
            'sku_id' => $skuId,
        ]);
        if (!isset($orderDetail)) {
            throw new \Exception('Order detail not found.');
        } elseif (!$orderDetail->order->checkAccess()) {
            throw new \Exception('You are not authorized to provide feedback for this order');
        }
        return $orderDetail;
    }
}
