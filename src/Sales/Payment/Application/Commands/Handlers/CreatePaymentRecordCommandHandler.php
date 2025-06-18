<?php

namespace Src\Sales\Payment\Application\Commands\Handlers;

use Src\Sales\Payment\Application\Commands\CreatePaymentRecordCommand;
// use Src\Sales\Payment\Domain\Events\OrderCreatedEvent;
use Src\Sales\Payment\Domain\Entities\PaymentRecord;
use Src\Sales\Payment\Domain\Repositories\PaymentRecordRepositoryInterface;
use Src\Sales\Payment\Domain\Repositories\PaymentScheduleRepositoryInterface;
use Src\Sales\Payment\Domain\Services\PaymentRecordDomainService;

final class CreatePaymentRecordCommandHandler
{
    private PaymentRecordRepositoryInterface $paymentRecordRepository;

    private PaymentScheduleRepositoryInterface $paymentScheduleRepository;

    public function __construct(
        PaymentRecordRepositoryInterface $paymentRecordRepository,
        PaymentScheduleRepositoryInterface $paymentScheduleRepository,
    ) {
        $this->paymentRecordRepository = $paymentRecordRepository;
        $this->paymentScheduleRepository = $paymentScheduleRepository;
    }

    public function handle(CreatePaymentRecordCommand $command): ?PaymentRecord
    {
        $paymentRecordEntitySaved = (new PaymentRecordDomainService(
            $this->paymentRecordRepository,
            $this->paymentScheduleRepository,
        ))
            ->create(
                $command->getPaymentScheduleId(),
            );

        if ($paymentRecordEntitySaved) {
            // OrderCreatedEvent::dispatch($orderEntitySaved, ['generateInvoice' => $command->getGenerateInvoice()]);
        }

        return $paymentRecordEntitySaved;

    }
}
