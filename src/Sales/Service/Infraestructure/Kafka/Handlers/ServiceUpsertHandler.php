<?php

namespace Src\Sales\Service\Infraestructure\Kafka\Handlers;

use Junges\Kafka\Contracts\ConsumerMessage;
use Src\Sales\Service\Domain\Entities\Service;
use Src\Sales\Service\Infraestructure\Repositories\ServiceRepository;

class ServiceUpsertHandler
{
    public function __invoke(ConsumerMessage $message)
    {
        $body = $message->getBody();

        $service = new Service(
            $body['service_id'],
            $body['name'],
            $body['unit'],
            $body['description'],
        );

        $serviceRepository = new ServiceRepository;
        $serviceRepository->upsert($service);
    }
}
