<?php

namespace Src\Sales\Payment\Application\Queries;

class GetPaymentQuery
{
    public string $paymentId;

    public function __construct(string $paymentId)
    {
        $this->paymentId = $paymentId;
    }
}
