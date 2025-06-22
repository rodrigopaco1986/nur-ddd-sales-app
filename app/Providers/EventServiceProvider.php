<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Src\Sales\Invoice\Application\Jobs\SendInvoiceEmailJob;
use Src\Sales\Invoice\Application\Listeners\CreateOrderInvoice;
use Src\Sales\Invoice\Application\Listeners\UpdateInvoiceStatus;
use Src\Sales\Invoice\Domain\Events\InvoiceCreatedEvent;
use Src\Sales\Invoice\Domain\Events\InvoiceDistpachedEvent;
use Src\Sales\Order\Application\Events\OrderCreatedIntegrationEvent;
use Src\Sales\Order\Application\Jobs\PublishOrderCreatedToBrokerJob;
use Src\Sales\Order\Application\Listeners\DispatchOrderCreatedIntegrationEvent;
use Src\Sales\Order\Domain\Events\OrderCreatedEvent;
use Src\Sales\Payment\Application\Listeners\CreateOrderPaymentSchedules;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        // DOMAIN EVENTS
        OrderCreatedEvent::class => [
            CreateOrderPaymentSchedules::class,
            CreateOrderInvoice::class,
            DispatchOrderCreatedIntegrationEvent::class,
        ],
        InvoiceCreatedEvent::class => [
            SendInvoiceEmailJob::class,
        ],
        InvoiceDistpachedEvent::class => [
            UpdateInvoiceStatus::class,
        ],
        // PaymentRegisteredEvent::class => [
        //    NotifyPaymentRegistered::class,
        // ],

        // INTEGRATION EVENTS
        OrderCreatedIntegrationEvent::class => [
            PublishOrderCreatedToBrokerJob::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
