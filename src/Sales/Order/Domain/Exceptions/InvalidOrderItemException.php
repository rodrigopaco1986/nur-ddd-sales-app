<?php

namespace Src\Sales\Order\Domain\Exceptions;

use App\Exceptions\DomainException;

class InvalidOrderItemException extends DomainException
{
    public function __construct()
    {
        parent::__construct('The order item is invalid.', 422);
    }
}
