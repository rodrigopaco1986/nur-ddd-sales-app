<?php

namespace Src\Sales\Payment\Application\Commands\Handlers;

use Src\Sales\Payment\Application\Commands\CreatePaymentRecordCommand;
//use Src\Sales\Payment\Domain\Events\OrderCreatedEvent;
use Src\Sales\Payment\Application\Services\OrderService;
use Src\Sales\Payment\Domain\Entities\PaymentRecord;
use Src\Sales\Payment\Domain\Repositories\PaymentRecordRepositoryInterface;
use Src\Sales\Payment\Domain\Repositories\PaymentScheduleRepositoryInterface;

final class CreatePaymentRecordCommandHandler
{
    private PaymentRecordRepositoryInterface $paymentRecordRepository;

    private PaymentScheduleRepositoryInterface $paymentScheduleRepository;

    private OrderService $orderService;

    public function __construct(
        PaymentRecordRepositoryInterface $paymentRecordRepository,
        PaymentScheduleRepositoryInterface $paymentScheduleRepository,
        //OrderService $orderService
    ) {
        $this->paymentRecordRepository = $paymentRecordRepository;
        $this->paymentScheduleRepository = $paymentScheduleRepository;
        //$this->orderService = $orderService;
    }

    public function handle(CreatePaymentRecordCommand $command): ?PaymentRecord
    {
        $paymentScheduleId = $command->getPaymentScheduleId();
        $paymentSchedule = $this->paymentScheduleRepository->findById($paymentScheduleId);

        $data = $this->paymentRecordRepository->save($paymentSchedule);

        return $data;
    }
}
