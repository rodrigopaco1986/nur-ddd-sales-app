<?php

namespace Tests\Feature\Order;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function itCanAccess(): void
    {
        $token = config('app.api_token');

        $response = $this->get('/', [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(200);
    }

    #[Test]
    public function itCannotAccessWithoutBearerToken(): void
    {
        $response = $this->get('/');

        $response->assertStatus(401);

    }
}
