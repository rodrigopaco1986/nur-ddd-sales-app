<?php

namespace Tests\Feature\Order;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PaymentQueryTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_query_a_scheduled_payment(): void
    {
        $token = config('app.api_token');
        $numberOfPayments = 2;

        $payload = [
            'patient_id' => (string) Str::orderedUuid(),
            'generate_invoice' => 1,
            'payment_installments' => $numberOfPayments,
            'items' => [
                [
                    'service_id' => (string) Str::orderedUuid(),
                    'quantity' => 1,
                    'price' => 100,
                    'discount' => 0,
                ],
            ],
        ];

        $response = $this->postJson('/order/create', $payload, [
            'Authorization' => "Bearer $token",
        ]);

        $order = $response->json();
        $orderId = $order['data']['order']['id'];

        $response = $this->getJson('/payment/view-by-order/' . $orderId, [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'payment' => [
                        'id',
                        'number',
                        'amount',
                        'due_date',
                        'status',
                        'currency',
                        'order_id',
                    ],
                ],
            ],
        ]);

        $response->assertJsonCount($numberOfPayments, 'data.*.payment');
    }
}
