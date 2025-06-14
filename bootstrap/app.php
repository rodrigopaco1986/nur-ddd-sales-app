<?php

use App\Http\Middleware\DBTransactionMiddleware;
use App\Http\Middleware\ValidateTokenMiddleware;
use App\Http\Middleware\ValidateUrlTokenMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Sentry\Laravel\Integration;

/*const LISTENERS = [
    __DIR__.'/../src/Sales/Order/Domain/Listeners',
    __DIR__.'/../src/Sales/Invoice/Domain/Listeners',
    __DIR__.'/../src/Sales/Payment/Domain/Listeners',
];*/

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        \Src\Sales\Order\Application\Providers\OrderProvider::class,
        \Src\Sales\Invoice\Application\Providers\InvoiceProvider::class,
        \Src\Sales\Patient\Application\Providers\PatientProvider::class,
        \Src\Sales\Service\Application\Providers\ServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            $routes = config('routes.web_routes');
            foreach ($routes as $route) {
                Route::middleware('api')
                    ->prefix($route['name'])
                    ->name($route['name'])
                    ->group(base_path($route['path']));
            }
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(DBTransactionMiddleware::class);
        // $middleware->append(ValidateTokenMiddleware::class);
        $middleware->append(ValidateUrlTokenMiddleware::class);
        $middleware->validateCsrfTokens(except: [
            'pact-state',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        Integration::handles($exceptions);
        /*$exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {

            if ($request->is(['order/*', 'invoice/*', 'payment/*'])) {
                return true;
            }

            return null;
        });*/
    })
    ->create();
