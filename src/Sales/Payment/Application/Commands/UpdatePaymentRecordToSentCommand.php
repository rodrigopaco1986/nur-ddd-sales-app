<?php

namespace Src\Sales\Payment\Application\Commands;

class UpdatePaymentRecordToSentCommand
{
    public function __construct(
        private string $paymentRecordId,
    ) {}

    /**
     * Get the value of Payment Record Id
     */
    public function getPaymentRecordId()
    {
        return $this->paymentRecordId;
    }

    /**
     * Set the value of Payment Record Id
     *
     * @return self
     */
    public function setPaymentId($paymentRecordId)
    {
        $this->paymentRecordId = $paymentRecordId;

        return $this;
    }
}
