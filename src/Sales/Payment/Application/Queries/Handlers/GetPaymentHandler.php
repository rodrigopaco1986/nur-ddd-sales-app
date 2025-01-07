<?php

namespace Src\Sales\Payment\Application\Queries\Handlers;

use Src\Sales\Payment\Application\Queries\GetPaymentQuery;
use Src\Sales\Payment\Domain\Entities\PaymentSchedule;
use Src\Sales\Payment\Domain\Repositories\PaymentScheduleRepositoryInterface;

class GetPaymentHandler
{
    private PaymentScheduleRepositoryInterface $paymentRepository;

    public function __construct(PaymentScheduleRepositoryInterface $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function handle(GetPaymentQuery $query): ?PaymentSchedule
    {
        $payment = $this->paymentRepository->findById($query->paymentId);

        if ($payment) {
            return $payment;
        }

        return null;
    }
}
