<?php

namespace Database\Factories\Order;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Src\Sales\Order\Infraestructure\Models\OrderItem;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Src\Sales\Order\Infraestructure\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $qty = $this->faker->randomNumber(1);
        $price = $this->faker->randomNumber(3);
        $discount = 0;

        return [
            'service_id' => (string) Str::orderedUuid(),
            'service_code' => $this->randomNumber(5, true),
            'service_name' => $this->faker->sentence(3),
            'service_unit' => $this->faker->word(),
            'quantity' => $this->faker->randomNumber(1),
            'price' => $this->faker->randomNumber(1),
            'discount' => $discount,
            'subtotal' => ($qty * $price) - $discount,
        ];
    }
}
