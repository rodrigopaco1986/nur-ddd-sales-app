<?php

namespace Src\Sales\Payment\Infraestructure\Repositories;

use DateTimeImmutable;
use Src\Sales\Invoice\Domain\Entities\Invoice;
use Src\Sales\Payment\Domain\Entities\PaymentRecord;
use Src\Sales\Payment\Domain\Entities\PaymentSchedule;
use Src\Sales\Payment\Domain\Repositories\PaymentRecordRepositoryInterface;
use Src\Sales\Payment\Domain\ValueObject\PaymentRecordStatus;
use Src\Sales\Payment\Infraestructure\Mappers\PaymentRecordMapper;
use Src\Sales\Payment\Infraestructure\Models\PaymentRecord as EloquentPaymentRecord;
use Src\Sales\Shared\Domain\ValueObject\Currency;
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

    public function findByPaymentSchedule(string $id): ?PaymentRecord
    {
        $eloquentPaymentRecord = EloquentPaymentRecord::where('payments_schedule_id', $id)->first();

        if ($eloquentPaymentRecord) {
            return PaymentRecordMapper::toEntity($eloquentPaymentRecord);
        }

        return null;
    }

    public function findByOrderId(string $orderId): ?array
    {
        $eloquentPaymentRecord = EloquentPaymentRecord::where('order_id', $orderId)->get();

        if ($eloquentPaymentRecord) {
            $response = [];
            foreach ($eloquentPaymentRecord as $paymentRecord) {
                $response[] = PaymentRecordMapper::toEntity($paymentRecord);
            }

            return $response;
        }

        return null;
    }

    public function save(PaymentSchedule $paymentSchedule, Invoice $invoice): ?PaymentRecord
    {
        $paymentRecord = new PaymentRecord(
            new Money($paymentSchedule->getAmount(), new Currency($paymentSchedule->getCurrency())),
            new DateTimeImmutable,
            new PaymentRecordStatus(PaymentRecordStatus::CREATED()->getStatus()),
            $invoice->getCustomerName(),
            '',
            $invoice->getNit(),
            $paymentSchedule->getOrderId(),
            $paymentSchedule->getId(),
        );

        $eloquentPaymentRecordModel = PaymentRecordMapper::toModel($paymentRecord);
        $eloquentPaymentRecordModel->save();

        return PaymentRecordMapper::toEntity($eloquentPaymentRecordModel);
    }

    public function updateToSentStatus(PaymentRecord $payment): ?PaymentRecord
    {
        $eloquentPayment = EloquentPaymentRecord::find($payment->getId());
        if ($eloquentPayment) {
            $eloquentPayment->fill([
                'status' => PaymentRecordStatus::DELIVERED()->getStatus(),
            ])->save();
        }

        return PaymentRecordMapper::toEntity($eloquentPayment);
    }
}
