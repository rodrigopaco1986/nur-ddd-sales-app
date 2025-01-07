<?php

namespace Src\Sales\Payment\Application\Queries\Handlers;

use Src\Sales\Payment\Application\Queries\GetPaymentsByOrderQuery;
use Src\Sales\Payment\Domain\Repositories\PaymentScheduleRepositoryInterface;

class GetPaymentsByOrderHandler
{
    private PaymentScheduleRepositoryInterface $paymentRepository;

    public function __construct(PaymentScheduleRepositoryInterface $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function handle(GetPaymentsByOrderQuery $query): ?array
    {
        $payment = $this->paymentRepository->findByOrderId($query->orderId);

        if ($payment) {
            return $payment;
        }

        return null;
    }
}
