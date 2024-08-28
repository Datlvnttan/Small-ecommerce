<?php

namespace Modules\Product\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FlashSaleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Product\Entities\FlashSale::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $startTime = $this->faker->dateTimeBetween('-10 days','-2days');
        $startDate = \DateTimeImmutable::createFromMutable($startTime);

        // Cộng thêm 6 tháng vào thời gian đã sinh ra
        $endDate = $startDate->add(new \DateInterval('P6M'));
        return [
            'start_time' => $startDate->format('Y-m-d H:i:s'), 
            'end_time' => $endDate->format('Y-m-d H:i:s'), 
        ];
    }
}

