<?php

namespace Src\Sales\Payment\Domain\Repositories;

use Src\Sales\Payment\Domain\Entities\PaymentRecord;
use Src\Sales\Payment\Domain\Entities\PaymentSchedule;

interface PaymentRecordRepositoryInterface
{
    public function findById(string $id): ?PaymentRecord;

    public function save(PaymentSchedule $paymentSchedule): ?PaymentRecord;
}
