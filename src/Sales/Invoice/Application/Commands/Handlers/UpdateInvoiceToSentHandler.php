<?php

namespace Src\Sales\Invoice\Application\Commands\Handlers;

use Src\Sales\Invoice\Application\Commands\UpdateInvoiceToSentCommand;
use Src\Sales\Invoice\Domain\Entities\Invoice;
use Src\Sales\Invoice\Domain\Repositories\InvoiceRepositoryInterface;

final class UpdateInvoiceToSentHandler
{
    private InvoiceRepositoryInterface $invoiceRepository;

    public function __construct(
        InvoiceRepositoryInterface $invoiceRepository,
    ) {
        $this->invoiceRepository = $invoiceRepository;
    }

    public function handle(UpdateInvoiceToSentCommand $command): ?Invoice
    {
        $invoice = $this->invoiceRepository->findById($command->getInvoiceId());

        if ($invoice) {
            $updatedInvoice = $this->invoiceRepository->updateToSentStatus($invoice);
        }

        return $updatedInvoice;
    }
}
