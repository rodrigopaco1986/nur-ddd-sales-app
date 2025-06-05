<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    App\Providers\TelescopeServiceProvider::class,
    Src\Sales\Invoice\Application\Providers\InvoiceProvider::class,
    Src\Sales\Order\Application\Providers\OrderProvider::class,
];
