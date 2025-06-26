<?php

namespace Src\Sales\Payment\Application\Jobs;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Src\Sales\Invoice\Application\Services\CompanyService;
use Src\Sales\Payment\Domain\Events\PaymentRecordDistpachedEvent;
use Src\Sales\Payment\Domain\Events\PaymentRecordRegisteredEvent;
use Src\Sales\Payment\Infraestructure\Mails\Payment as PaymentMailable;
use Src\Sales\Payment\Presentation\Resources\PaymentRecordResource;

class SendPaymentRecordEmailJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 1;

    /**
     * Execute the job.
     */
    public function handle(PaymentRecordRegisteredEvent $paymentRegisteredEvent): void
    {
        $paymentRecord = $paymentRegisteredEvent->payment;
        $customerEmail = $paymentRegisteredEvent->customerEmail;

        Log::info("Generating PDF for payment record #{$paymentRecord->getId()}");

        $paymentData = json_decode((new PaymentRecordResource($paymentRecord))->toJson());

        $companyInfo = (new CompanyService)->getInfo();

        $pdf = Pdf::loadView('payment::Pdf.payment', ['payment' => $paymentData->payment, 'company' => $companyInfo]);
        $pdfContent = $pdf->output();

        Log::info('Sending payment record email to ' . $customerEmail);
        Mail::to($customerEmail)->send(
            new PaymentMailable($pdfContent, $paymentRecord->getId())
        );

        Log::info("Successfully sent payment record #{$paymentRecord->getId()}");

        PaymentRecordDistpachedEvent::dispatch($paymentRecord);
    }
}
