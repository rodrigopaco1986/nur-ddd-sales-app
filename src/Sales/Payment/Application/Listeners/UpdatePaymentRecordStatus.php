<?php

namespace Src\Sales\Payment\Application\Listeners;

use Src\Sales\Payment\Application\Commands\Handlers\UpdatePaymentRecordToSentHandler;
use Src\Sales\Payment\Application\Commands\UpdatePaymentRecordToSentCommand;
use Src\Sales\Payment\Domain\Entities\PaymentRecord;
use Src\Sales\Payment\Domain\Events\PaymentRecordDistpachedEvent;
use Src\Sales\Payment\Infraestructure\Repositories\PaymentRecordRepository;

class UpdatePaymentRecordStatus
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
    public function handle(PaymentRecordDistpachedEvent $paymentEvent): ?PaymentRecord
    {
        $paymentRecord = $paymentEvent->paymentRecord;

        if ($paymentRecord) {
            $commandPaymentRecord = new UpdatePaymentRecordToSentCommand($paymentRecord->getId());
            $commandPaymentRecordHandlerResponse = (new UpdatePaymentRecordToSentHandler(
                new PaymentRecordRepository,
            )
            )
                ->handle($commandPaymentRecord);

            return $commandPaymentRecordHandlerResponse;
        }

        return null;
    }
}
