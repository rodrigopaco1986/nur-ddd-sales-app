<?php

namespace Tests\Feature\Order;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_access(): void
    {
        $token = config('app.api_token');

        $response = $this->get('/', [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(200);
    }

    #[Test]
    public function it_cannot_access_without_bearer_token(): void
    {
        $response = $this->get('/');

        $response->assertStatus(401);

    }
}
