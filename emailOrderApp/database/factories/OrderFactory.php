<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'customer_name' => $this->faker->name,
            'customer_email' => $this->faker->safeEmail,
            'order_title' => $this->faker->words(3, true),
            'order_details' => $this->faker->sentence,
            'status' => 'Pending',
        ];
    }
}
