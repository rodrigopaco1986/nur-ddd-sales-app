<?php

namespace Src\Sales\Order\Infraestructure\Exceptions;

use App\Exceptions\DomainException;

class KafkaNotificationException extends DomainException
{
    public function __construct(string $topic)
    {
        parent::__construct('There was an error delivering to kafka the topic: {$topic}.', 422);
    }
}
