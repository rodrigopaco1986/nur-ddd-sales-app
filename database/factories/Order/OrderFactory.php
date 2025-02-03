<?php

namespace Database\Factories\Order;

use App\Services\Helper as H;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Src\Sales\Order\Domain\ValueObject\OrderStatus;
use Src\Sales\Order\Infraestructure\Models\Order;
use Src\Sales\Shared\Domain\ValueObject\Currency;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Src\Sales\Order\Infraestructure\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patient_id' => (string) Str::orderedUuid(),
            'order_date' => new DateTimeImmutable,
            'status' => OrderStatus::CREATED(),
            'currency' => new Currency(H::getDefaultCurrencyCode()),
            'total' => $this->randomNumber(3, true),
        ];
    }
}
