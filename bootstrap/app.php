<?php

use App\Http\Middleware\DBTransactionMiddleware;
use App\Http\Middleware\ValidateTokenMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

const ROUTES = [
    ['name' => 'order', 'path' => 'src/Sales/Order/Application/Routes/web.php'],
    ['name' => 'invoice', 'path' => 'src/Sales/Invoice/Application/Routes/web.php'],
    ['name' => 'payment', 'path' => 'src/Sales/Payment/Application/Routes/web.php'],
];

const LISTENERS = [
    __DIR__.'/../src/Sales/Order/Domain/Listeners',
    __DIR__.'/../src/Sales/Invoice/Domain/Listeners',
    __DIR__.'/../src/Sales/Payment/Domain/Listeners',
];

return Application::configure(basePath: dirname(__DIR__))
    //->withEvents(discover: LISTENERS)
    ->withProviders([
        \Src\Sales\Order\Application\Providers\OrderProvider::class,
        \Src\Sales\Invoice\Application\Providers\InvoiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            foreach (ROUTES as $route) {
                Route::middleware('api')
                    ->prefix($route['name'])
                    ->name($route['name'])
                    ->group(base_path($route['path']));
            }
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(DBTransactionMiddleware::class);
        $middleware->append(ValidateTokenMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
