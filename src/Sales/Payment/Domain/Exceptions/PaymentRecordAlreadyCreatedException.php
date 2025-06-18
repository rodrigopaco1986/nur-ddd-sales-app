<?php

namespace Src\Sales\Payment\Domain\Exceptions;

use App\Exceptions\DomainException;

class PaymentRecordAlreadyCreatedException extends DomainException
{
    public function __construct(private string $paymentId)
    {
        parent::__construct("The payment record $paymentId was registered before!", 422);
    }
}
