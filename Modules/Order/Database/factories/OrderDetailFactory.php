<?php

namespace Modules\Order\Database\factories;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Log;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderDetail;
use Modules\Order\Enums\OrderStatus;
use Modules\Product\Entities\Sku;

class OrderDetailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Order\Entities\OrderDetail::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $skuId = null;
        $orderId = null;
        $orderDetail = null;
        do {
            $order = Order::inRandomOrder()->first();
            $sku = Sku::inRandomOrder()->first();
            $skuId = $sku->id;
            $orderId = $order->id;
            $orderDetail = OrderDetail::where('order_id', $orderId)
            ->where('sku_id', $skuId)->first();
        } while (isset($orderDetail));
        $price = $sku->guest_price;
        if (isset($order->user_id)) {
            $price = $sku->getAttribute("member_{$order->user->member_type}_price");
        }
        $quantity = $this->faker->numberBetween(1, 300);
        $data = [
            'order_id' => $orderId,
            'sku_id' => $skuId,
            'price' => $price,
            'quantity' => $quantity,
            'options' => $sku->getOptionsName()
        ];
        $order->total_amount += $quantity * $price;
        $order->save();
        // $order = Order::find($orderId);
        if ($this->faker->boolean(70)) {
            $feedback_rating = $this->faker->numberBetween(1, 5);
            $data['feedback_rating'] = $feedback_rating;
            $data['feedback_image'] = $this->faker->boolean ? Helper::randomOTP(fake()->numberBetween(3, 6)) . '.jpg' : null;
            $data['feedback_title'] = $this->faker->words(fake()->numberBetween(3, 15), true);
            $data['feedback_review'] = $this->faker->words(fake()->numberBetween(5, 40), true);
            $data['feedback_status'] = $this->faker->boolean;
            $data['feedback_created_at'] = $this->faker->dateTime();
            $data['feedback_incognito'] = $this->faker->boolean;
        }
        return $data;
    }
}
