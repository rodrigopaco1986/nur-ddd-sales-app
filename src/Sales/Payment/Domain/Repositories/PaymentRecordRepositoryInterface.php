<?php

namespace Src\Sales\Payment\Domain\Repositories;

use Src\Sales\Payment\Domain\Entities\PaymentRecord;
use Src\Sales\Payment\Domain\Entities\PaymentSchedule;

interface PaymentRecordRepositoryInterface
{
    public function findById(string $id): ?PaymentRecord;

    public function findByPaymentSchedule(string $id): ?PaymentRecord;

    public function findByOrderId(string $orderId): ?array;

    public function save(PaymentSchedule $paymentSchedule): ?PaymentRecord;
}
