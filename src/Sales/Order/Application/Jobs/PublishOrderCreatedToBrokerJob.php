<?php

namespace Src\Sales\Order\Application\Jobs;

use App\Notifications\NotificationProducerInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Sales\Order\Application\Events\OrderCreatedIntegrationEvent;
use Src\Sales\Order\Application\Queries\GetOrderQuery;
use Src\Sales\Order\Application\Queries\Handlers\GetOrderHandler;
use Src\Sales\Order\Infraestructure\Repositories\OrderRepository;
use Src\Sales\Order\Presentation\Resources\OrderResource;

class PublishOrderCreatedToBrokerJob implements ShouldQueue
{
    use Dispatchable;
    use Queueable;
    use SerializesModels;

    public function __construct(private NotificationProducerInterface $notificationProducerInterface) {}

    public function handle(OrderCreatedIntegrationEvent $orderEvent)
    {
        $orderId = $orderEvent->order->getId();

        $queryOrder = new GetOrderQuery($orderId);
        $queryOrderHandlerResponse = (new GetOrderHandler(new OrderRepository))
            ->handle($queryOrder);

        $orderResource = new OrderResource($queryOrderHandlerResponse);
        $orderResourceDecoded = json_decode($orderResource->toJson(), true);

        $this->notificationProducerInterface->publish('order.created', null, $orderResourceDecoded);
    }
}
