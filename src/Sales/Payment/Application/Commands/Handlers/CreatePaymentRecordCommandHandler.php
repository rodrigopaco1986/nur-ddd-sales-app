<?php

namespace Src\Sales\Payment\Application\Commands\Handlers;

use Src\Sales\Payment\Application\Commands\CreatePaymentRecordCommand;
// use Src\Sales\Payment\Domain\Events\OrderCreatedEvent;
use Src\Sales\Payment\Application\Services\InvoiceService;
use Src\Sales\Payment\Domain\Entities\PaymentRecord;
use Src\Sales\Payment\Domain\Events\PaymentRecordRegisteredEvent;
use Src\Sales\Payment\Domain\Repositories\PaymentRecordRepositoryInterface;
use Src\Sales\Payment\Domain\Repositories\PaymentScheduleRepositoryInterface;
use Src\Sales\Payment\Domain\Services\PaymentRecordDomainService;

final class CreatePaymentRecordCommandHandler
{
    private PaymentRecordRepositoryInterface $paymentRecordRepository;

    private PaymentScheduleRepositoryInterface $paymentScheduleRepository;

    private InvoiceService $invoiceService;

    public function __construct(
        PaymentRecordRepositoryInterface $paymentRecordRepository,
        PaymentScheduleRepositoryInterface $paymentScheduleRepository,
        InvoiceService $invoiceService
    ) {
        $this->paymentRecordRepository = $paymentRecordRepository;
        $this->paymentScheduleRepository = $paymentScheduleRepository;
        $this->invoiceService = $invoiceService;
    }

    public function handle(CreatePaymentRecordCommand $command): ?PaymentRecord
    {
        $paymentRecordEntitySaved = (new PaymentRecordDomainService(
            $this->paymentRecordRepository,
            $this->paymentScheduleRepository,
            $this->invoiceService,
        ))
            ->create(
                $command->getPaymentScheduleId(),
            );

        if ($paymentRecordEntitySaved) {
            $invoiceInfo = $this->invoiceService->getInvoiceInfo($paymentRecordEntitySaved->getOrderId());
            PaymentRecordRegisteredEvent::dispatch($paymentRecordEntitySaved, $invoiceInfo->getCustomerEmail());
        }

        return $paymentRecordEntitySaved;

    }
}
