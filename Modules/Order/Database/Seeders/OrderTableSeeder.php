<?php

namespace Modules\Order\Database\Seeders;

use DateInterval;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\OrderStatus;

class OrderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::factory()->count(120)->create();
        // $orders = Order::all();
        // foreach ($orders as $order) {
            // if(!isset($order->email))
            // {
            //     $order->email = 'Datlvnttan@gmail.com';
            //     $order->save();
            // }
            // $statusValue = fake()->randomElement(OrderStatus::values());
            // $status = [];
            // $time = fake()->dateTime();
            // if($statusValue == OrderStatus::Cancelled->value)
            // {
            //     $status['Cancelled'] = [
            //         'at' => $time->format('Y-m-d H:i:s'),
            //         'reason' => fake()->text(fake()->numberBetween(10,35)),
            //     ];
            // }
            // elseif($statusValue == OrderStatus::Rejected->value)
            // {
            //     $status['Rejected'] = [
            //         'at' => $time->format('Y-m-d H:i:s'),
            //         'reason' => fake()->text(fake()->numberBetween(5,15)),
            //     ];
            // }
            // elseif($statusValue == OrderStatus::AwaitingVerification->value)
            // {
            //     $status['Awaiting Verification'] = $time->format('Y-m-d H:i:s');
            // }
            // elseif($statusValue == OrderStatus::AwaitingConfirmation->value)
            // {
            //     $status['Awaiting Verification'] = $time->format('Y-m-d H:i:s');
            //     $time = $this->randomDateTime($time);
            //     $status['Awaiting Confirmation'] = $time->format('Y-m-d H:i:s');
            // }
            // elseif($statusValue == OrderStatus::Processing->value)
            // {
            //     $status['Awaiting Verification'] = $time->format('Y-m-d H:i:s');
            //     $time = $this->randomDateTime($time);
            //     $status['Awaiting Confirmation'] = $time->format('Y-m-d H:i:s');
            //     $time = $this->randomDateTime($time);
            //     $status['Processing'] = $time->format('Y-m-d H:i:s');
            // }
            // elseif($statusValue == OrderStatus::InTransit->value)
            // {
            //     $status['Awaiting Verification'] = $time->format('Y-m-d H:i:s');
            //     $time = $this->randomDateTime($time);
            //     $status['Awaiting Confirmation'] = $time->format('Y-m-d H:i:s');
            //     $time = $this->randomDateTime($time);
            //     $status['Processing'] = $time->format('Y-m-d H:i:s');
            //     $time = $this->randomDateTime($time);
            //     $status['In Transit'] = $time->format('Y-m-d H:i:s');
            // }
            // elseif($statusValue == OrderStatus::Delivered->value)
            // {
            //     $status['Awaiting Verification'] = $time->format('Y-m-d H:i:s');
            //     $time = $this->randomDateTime($time);
            //     $status['Awaiting Confirmation'] = $time->format('Y-m-d H:i:s');
            //     $time = $this->randomDateTime($time);
            //     $status['Processing'] = $time->format('Y-m-d H:i:s');
            //     $time = $this->randomDateTime($time);
            //     $status['In Transit'] = $time->format('Y-m-d H:i:s');
            //     $time = $this->randomDateTime($time);
            //     $status['Delivered'] = $time->format('Y-m-d H:i:s');
            // }
            // $order->update([
            //     'status' => json_encode($status),
            // ]);
        // }
        // $this->call("OthersTableSeeder");
    }
    private function randomDateTime($time)
    {
        $intRan = fake()->numberBetween(1,30);
        $interval = new DateInterval("P{$intRan}D");
        $time->add($interval);
        return $time;
    }
}
