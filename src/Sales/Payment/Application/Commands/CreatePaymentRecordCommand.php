<?php

namespace Src\Sales\Payment\Application\Commands;

class CreatePaymentRecordCommand
{
    public function __construct(
        private string $paymentScheduleId,
    ) {}

    /**
     * Get the value of paymentScheduleId
     */
    public function getPaymentScheduleId()
    {
        return $this->paymentScheduleId;
    }

    /**
     * Set the value of paymentScheduleId
     *
     * @return self
     */
    public function setPaymentScheduleId($paymentScheduleId)
    {
        $this->paymentScheduleId = $paymentScheduleId;

        return $this;
    }
}
