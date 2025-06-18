<?php

namespace Tests\Feature\Order;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrderCreationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_cannot_create_an_order_invalid_empty_post(): void
    {
        $token = config('app.api_token');

        $payload = [

        ];

        $response = $this->postJson('/order/create', $payload, [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                'patient_id' => [
                    'validation.required',
                ],
                'payment_installments' => [
                    'validation.required',
                ],
                'generate_invoice' => [
                    'validation.required',
                ],
                'items' => [
                    'validation.required',
                ],
            ],
        ]);
    }

    #[Test]
    public function it_cannot_create_an_order_invalid_patient_id(): void
    {
        $token = config('app.api_token');

        $payload = [
            'patient_id' => 'XYZ',
            'generate_invoice' => 1,
            'payment_installments' => 2,
            'items' => [
                [
                    'service_id' => (string) Str::orderedUuid(),
                    'quantity' => 1,
                    'price' => 100,
                    'discount' => 0,
                ],
                [
                    'service_id' => (string) Str::orderedUuid(),
                    'quantity' => 2,
                    'price' => 400,
                    'discount' => 100,
                ],
            ],
        ];

        $response = $this->postJson('/order/create', $payload, [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                'patient_id' => [
                    'validation.uuid',
                ],
            ],
        ]);
    }

    #[Test]
    public function it_can_create_an_order(): void
    {
        $token = config('app.api_token');

        $payload = [
            'patient_id' => (string) Str::orderedUuid(),
            'generate_invoice' => 1,
            'payment_installments' => 2,
            'items' => [
                [
                    'service_id' => (string) Str::orderedUuid(),
                    'quantity' => 1,
                    'price' => 100,
                    'discount' => 0,
                ],
                [
                    'service_id' => (string) Str::orderedUuid(),
                    'quantity' => 2,
                    'price' => 400,
                    'discount' => 100,
                ],
            ],
        ];

        $response = $this->postJson('/order/create', $payload, [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'order' => [
                    'id',
                    'patient_id',
                    'order_date',
                    'status',
                    'currency',
                    'total',
                    'items' => [
                        '*' => [
                            'id',
                            'service_id',
                            'service_code',
                            'service_name',
                            'service_unit',
                            'quantity',
                            'price',
                            'discount',
                            'subtotal',
                        ],
                    ],
                ],
            ],
        ]);
    }

    #[Test]
    public function it_cannot_create_an_order_invalid_item(): void
    {
        $token = config('app.api_token');

        $payload = [
            'patient_id' => (string) Str::orderedUuid(),
            'generate_invoice' => 1,
            'payment_installments' => 2,
            'items' => [
                [
                    'service_id' => '',
                    'quantity' => 1,
                    'price' => 100,
                    'discount' => 0,
                ],
            ],
        ];

        $response = $this->postJson('/order/create', $payload, [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                'items.0.service_id' => [
                    'validation.required',
                ],
            ],
        ]);
    }

    #[Test]
    public function it_can_query_an_order(): void
    {
        $token = config('app.api_token');

        $payload = [
            'patient_id' => (string) Str::orderedUuid(),
            'generate_invoice' => 1,
            'payment_installments' => 2,
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

        $response = $this->getJson('/order/view/' . $orderId, [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'order' => [
                    'id',
                    'patient_id',
                    'order_date',
                    'status',
                    'currency',
                    'total',
                    'items' => [
                        '*' => [
                            'id',
                            'service_id',
                            'service_code',
                            'service_name',
                            'service_unit',
                            'quantity',
                            'price',
                            'discount',
                            'subtotal',
                        ],
                    ],
                ],
            ],
        ]);
    }

    #[Test]
    public function it_cannot_query_an_order_that_does_not_exists(): void
    {
        $token = config('app.api_token');

        $orderId = 'xxxxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';

        $response = $this->getJson('/order/view/' . $orderId, [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(404);
        $response->assertJson([
            'errors' => 'Order not found',
        ]);

    }
}
