<?php

namespace Src\Sales\Invoice\Application\Listeners;

use Src\Sales\Invoice\Application\Commands\Handlers\UpdateInvoiceToSentHandler;
use Src\Sales\Invoice\Application\Commands\UpdateInvoiceToSentCommand;
use Src\Sales\Invoice\Domain\Entities\Invoice;
use Src\Sales\Invoice\Domain\Events\InvoiceDistpachedEvent;
use Src\Sales\Invoice\Infraestructure\Repositories\InvoiceRepository;

class UpdateInvoiceStatus
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(InvoiceDistpachedEvent $invoiceEvent): ?Invoice
    {
        $invoice = $invoiceEvent->invoice;

        if ($invoice) {
            $commandInvoice = new UpdateInvoiceToSentCommand($invoice->getId());
            $commandInvoiceHandlerResponse = (new UpdateInvoiceToSentHandler(
                new InvoiceRepository,
            )
            )
                ->handle($commandInvoice);

            return $commandInvoiceHandlerResponse;
        }

        return null;
    }
}
