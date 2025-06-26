<?php

namespace Src\Sales\Payment\Application\Commands\Handlers;

use Src\Sales\Payment\Application\Commands\UpdatePaymentRecordToSentCommand;
use Src\Sales\Payment\Domain\Entities\PaymentRecord;
use Src\Sales\Payment\Domain\Repositories\PaymentRecordRepositoryInterface;

final class UpdatePaymentRecordToSentHandler
{
    private PaymentRecordRepositoryInterface $paymentRecordRepository;

    public function __construct(
        PaymentRecordRepositoryInterface $paymentRecordRepository,
    ) {
        $this->paymentRecordRepository = $paymentRecordRepository;
    }

    public function handle(UpdatePaymentRecordToSentCommand $command): ?PaymentRecord
    {
        $payment = $this->paymentRecordRepository->findById($command->getPaymentRecordId());

        if ($payment) {
            $updatedPayment = $this->paymentRecordRepository->updateToSentStatus($payment);
        }

        return $updatedPayment;
    }
}
