<?php

namespace Tests\Feature\Order;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class InvoiceCreationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function itCanCreateAnInvoice(): void
    {
        $token = config('app.api_token');

        $payload = [
            'patient_id' => (string) Str::orderedUuid(),
            'generate_invoice' => 0,
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
        $payload = [
            'order_id' => $orderId,
            'customer_id' => (string) Str::orderedUuid(),
        ];

        $response = $this->postJson('/invoice/create', $payload, [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'invoice' => [
                    'id',
                ],
            ],
        ]);
    }

    #[Test]
    public function itCanNotCreateAnInvoiceAlreadyGenerate(): void
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
        $payload = [
            'order_id' => $orderId,
            'customer_id' => (string) Str::orderedUuid(),
        ];

        $response = $this->postJson('/invoice/create', $payload, [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertJsonFragment([
            'message' => 'The invoice was already created!',
            'exception' => \Src\Sales\Invoice\Domain\Exceptions\InvoiceAlredyCreatedException::class,
        ]);

    }

    #[Test]
    public function itCanQueryAnInvoice(): void
    {
        $token = config('app.api_token');

        $payload = [
            'patient_id' => (string) Str::orderedUuid(),
            'generate_invoice' => 0,
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
        $payload = [
            'order_id' => $orderId,
            'customer_id' => (string) Str::orderedUuid(),
        ];

        $response = $this->postJson('/invoice/create', $payload, [
            'Authorization' => "Bearer $token",
        ]);

        $invoice = $response->json();
        $invoiceId = $invoice['data']['invoice']['id'];

        $response = $this->getJson('/invoice/view/'.$invoiceId, [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'invoice' => [
                    'id',
                ],
            ],
        ]);
    }
}
