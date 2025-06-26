<?php

namespace Src\Sales\Payment\Application\Services;

use Src\Sales\Invoice\Domain\Entities\Invoice;
use Src\Sales\Invoice\Domain\Repositories\InvoiceRepositoryInterface;

class InvoiceService
{
    private InvoiceRepositoryInterface $invoiceRepository;

    public function __construct(InvoiceRepositoryInterface $invoiceRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
    }

    public function getInvoiceInfo(string $orderId): ?Invoice
    {
        return $this->invoiceRepository->findByOrderId($orderId);
    }
}
