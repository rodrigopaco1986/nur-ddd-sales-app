<?php

namespace Src\Sales\Payment\Application\Queries;

class GetPaymentsByOrderQuery
{
    public string $orderId;

    public function __construct(string $orderId)
    {
        $this->orderId = $orderId;
    }
}
