<?php

namespace Modules\Order\Enums;

enum OrderStatus: string
{

    //Chờ xác thực
    case AwaitingVerification = 'Awaiting Verification';
    //Chờ xác nhận
    case AwaitingConfirmation = 'Awaiting Confirmation';
    //Đang xử lý
    case Processing = 'Processing';
    //Đang vận chuyển
    case InTransit = 'In Transit';
    //Đã giao
    case Delivered = 'Delivered';
    //Đã hủy
    case Cancelled = 'Cancelled';
    //Bị từ chối
    case Rejected = 'Rejected';

    
    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
    public static function names(): array
    {
        return array_map(fn($case) => $case->name, self::cases());
    }

    public static function getOrderedValues(): array {
        return [
            self::AwaitingVerification,
            self::AwaitingConfirmation,
            self::Processing,
            self::InTransit,
            self::Delivered,
            self::Cancelled,
            self::Rejected,
        ];
    }

    public static function getTheNextStatus(self $statusToFind): self|null {
        $orderedValues = self::getOrderedValues();
        $position = array_search($statusToFind, $orderedValues, true);
        if($position!=false)
        {
            if($position<4)
            {
                return $orderedValues[$position+1];
            }
        }
        return null;
    }
}
