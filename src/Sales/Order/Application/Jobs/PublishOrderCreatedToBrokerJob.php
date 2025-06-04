<?php

namespace Src\Sales\Order\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
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

    public function __construct() {}

    public function handle(OrderCreatedIntegrationEvent $orderEvent/* Producer $kafkaProducer */)
    {
        $orderId = $orderEvent->order->getId();

        $queryOrder = new GetOrderQuery($orderId);
        $queryOrderHandlerResponse = (new GetOrderHandler(new OrderRepository))
            ->handle($queryOrder);

        $orderResource = new OrderResource($queryOrderHandlerResponse);

        Log::info('Testint to kafka', ['data' => $orderResource->toJson()]);

        // $kafkaProducer->produce('orders.created', json_encode($payload));
    }
}
