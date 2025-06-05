<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateUrlTokenMiddleware
{
    /**
     * Handle an incoming request to validate a token in the headers.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('ui/jobs*')) {

            $token = $request->get('token');

            if (! $this->isValidToken($token)) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'message' => 'Invalid or missing token.',
                ], Response::HTTP_UNAUTHORIZED);
            }
        }

        return $next($request);
    }

    /**
     * Check if the provided token is valid.
     */
    private function isValidToken(?string $token): bool
    {
        $validToken = config('app.queue_monitor_token');

        return $validToken === $token;
    }
}
