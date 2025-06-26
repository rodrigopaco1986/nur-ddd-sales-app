<?php

namespace Src\Sales\Payment\Domain\Exceptions;

use App\Exceptions\DomainException;

class InvoiceNotFoundException extends DomainException
{
    public function __construct(private string $orderId)
    {
        parent::__construct("The invoice was not found for order $orderId! Generate it first!", 404);
    }
}
