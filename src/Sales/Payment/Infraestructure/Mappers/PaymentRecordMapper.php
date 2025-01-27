<?php

namespace Src\Sales\Payment\Infraestructure\Mappers;

use Src\Sales\Payment\Domain\Entities\PaymentRecord as PaymentRecordEntity;
use Src\Sales\Payment\Infraestructure\Models\PaymentRecord as EloquentPaymentRecord;
use Src\Sales\Shared\Domain\ValueObject\Currency;
use Src\Sales\Shared\Domain\ValueObject\Money;

class PaymentRecordMapper
{
    public static function toEntity(EloquentPaymentRecord $paymentRecord): PaymentRecordEntity
    {
        $paymentRecordEntity = new PaymentRecordEntity(
            new Money($paymentRecord->amount, new Currency($paymentRecord->currency)),
            $paymentRecord->payed_date,
            $paymentRecord->first_name,
            $paymentRecord->last_name,
            $paymentRecord->dni,
            $paymentRecord->order_id,
            $paymentRecord->payments_schedule_id,
        );
        $paymentRecordEntity->setId($paymentRecord->id);

        return $paymentRecordEntity;
    }

    public static function toModel(PaymentRecordEntity $paymentRecord): EloquentPaymentRecord
    {
        $eloquentPaymentRecordModel = (new EloquentPaymentRecord)
            ->fill([
                'amount' => $paymentRecord->getAmount(),
                'currency' => $paymentRecord->getCurrency(),
                'payed_date' => $paymentRecord->getPayedDate(),
                'first_name' => $paymentRecord->getFirstName(),
                'last_name' => $paymentRecord->getLastName(),
                'dni' => $paymentRecord->getDni(),
                'order_id' => $paymentRecord->getOrderId(),
                'payments_schedule_id' => $paymentRecord->getPaymentScheduleId(),
            ]);

        return $eloquentPaymentRecordModel;
    }
}
