<?php

namespace Src\Sales\Invoice\Application\Commands;

class UpdateInvoiceToSentCommand
{
    public function __construct(
        private string $invoiceId,
    ) {}

    /**
     * Get the value of invoiceId
     */
    public function getInvoiceId()
    {
        return $this->invoiceId;
    }

    /**
     * Set the value of invoiceId
     *
     * @return self
     */
    public function setInvoiceId($invoiceId)
    {
        $this->invoiceId = $invoiceId;

        return $this;
    }
}
