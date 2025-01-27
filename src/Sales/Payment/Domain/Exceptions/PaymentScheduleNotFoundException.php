<?php

namespace Src\Sales\Payment\Domain\Exceptions;

use App\Exceptions\DomainException;

class PaymentScheduleNotFoundException extends DomainException
{
    public function __construct(private string $paymentId)
    {
        parent::__construct("The payment schedule $paymentId was not found!", 404);
    }
}
