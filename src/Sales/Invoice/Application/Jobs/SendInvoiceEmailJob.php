<?php

namespace Src\Sales\Invoice\Application\Jobs;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Src\Sales\Invoice\Application\Services\CompanyService;
use Src\Sales\Invoice\Domain\Events\InvoiceCreatedEvent;
use Src\Sales\Invoice\Domain\Events\InvoiceDistpachedEvent;
use Src\Sales\Invoice\Infraestructure\Mails\Invoice as InvoiceMailable;
use Src\Sales\Invoice\Presentation\Resources\InvoiceResource;

class SendInvoiceEmailJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 1;

    /**
     * Execute the job.
     */
    public function handle(InvoiceCreatedEvent $invoiceCreatedEvent): void
    {
        $invoice = $invoiceCreatedEvent->invoice;
        $patient = $invoiceCreatedEvent->patient;

        Log::info("Generating PDF for invoice #{$invoice->getId()}");

        $invoiceData = json_decode((new InvoiceResource($invoice))->toJson());
        $invoiceData->invoice->total = $invoice->getTotal();

        $companyInfo = (new CompanyService)->getInfo();

        $pdf = Pdf::loadView('invoice::Pdf.invoice', ['invoice' => $invoiceData->invoice, 'company' => $companyInfo]);
        $pdfContent = $pdf->output();

        Log::info('Sending invoice email to ' . $patient->getEmail());
        Mail::to($patient->getEmail())->send(
            new InvoiceMailable($pdfContent, $invoice->getId())
        );

        Log::info("Successfully sent invoice #{$invoice->getId()}");

        InvoiceDistpachedEvent::dispatch($invoice);
    }
}
