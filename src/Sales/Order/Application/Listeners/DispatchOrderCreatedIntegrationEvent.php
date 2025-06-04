<?php

namespace Src\Sales\Order\Application\Listeners;

use Src\Sales\Order\Application\Events\OrderCreatedIntegrationEvent;
use Src\Sales\Order\Domain\Events\OrderCreatedEvent;

class DispatchOrderCreatedIntegrationEvent
{
    /**
     * Handle the event.
     */
    public function handle(OrderCreatedEvent $event): void
    {
        OrderCreatedIntegrationEvent::dispatch($event->order);
    }
}
