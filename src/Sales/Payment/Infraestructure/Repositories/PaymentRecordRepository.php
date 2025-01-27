<?php

namespace Src\Sales\Payment\Infraestructure\Repositories;

use Src\Sales\Payment\Domain\Entities\PaymentRecord;
use Src\Sales\Payment\Domain\Entities\PaymentSchedule;
use Src\Sales\Payment\Domain\Repositories\PaymentRecordRepositoryInterface;
use Src\Sales\Payment\Infraestructure\Mappers\PaymentRecordMapper;
use Src\Sales\Payment\Infraestructure\Models\PaymentRecord as EloquentPaymentRecord;
use Src\Sales\Shared\Domain\ValueObject\Currency;
use DateTimeImmutable;
use Src\Sales\Shared\Domain\ValueObject\Money;

class PaymentRecordRepository implements PaymentRecordRepositoryInterface
{
    public function findById(string $id): ?PaymentRecord
    {
        $eloquentPaymentRecord = EloquentPaymentRecord::find($id);

        if ($eloquentPaymentRecord) {
            return PaymentRecordMapper::toEntity($eloquentPaymentRecord);
        }

        return null;
    }

    public function save(PaymentSchedule $paymentSchedule): ?PaymentRecord
    {
        $paymentRecord = new PaymentRecord(
            new Money($paymentSchedule->getAmount(), new Currency($paymentSchedule->getCurrency())),
            new DateTimeImmutable,
            'rodrigo',
            'paco',
            '5852695',
            $paymentSchedule->getOrderId(),
            $paymentSchedule->getId(),
        );

        $eloquentPaymentRecordModel = PaymentRecordMapper::toModel($paymentRecord);
        $eloquentPaymentRecordModel->save();

        return PaymentRecordMapper::toEntity($eloquentPaymentRecordModel);
    }
}
