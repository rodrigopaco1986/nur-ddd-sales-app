<?php

namespace Src\Sales\Payment\Domain\Services;

use App\Exceptions\DomainException;
use DateTimeImmutable;
use Exception;
use Src\Sales\Payment\Domain\Entities\PaymentRecord;
use Src\Sales\Payment\Domain\Entities\PaymentSchedule;
use Src\Sales\Payment\Domain\Exceptions\OrderNotFoundException;
use Src\Sales\Payment\Domain\Exceptions\PaymentScheduleNotFoundException;
use Src\Sales\Payment\Domain\Exceptions\ValueException;
use Src\Sales\Payment\Domain\Repositories\PaymentRecordRepositoryInterface;
use Src\Sales\Shared\Domain\ValueObject\Currency;
use Src\Sales\Shared\Domain\ValueObject\Money;

class PaymentRecordDomainService
{
    private PaymentRecordRepositoryInterface $paymentRecordRepository;

    public function __construct(
        PaymentRecordRepositoryInterface $paymentRecordRepository,
    ) {
        $this->paymentRecordRepository = $paymentRecordRepository;
    }

    public function create(PaymentSchedule $paymentScheduleInfo, Currency $currency, array $data, string $paymentScheduleId): ?array
    {
        //TODO: finish this method
        return [];
        /*if (! $paymentScheduleInfo->getId()) {
            throw new PaymentScheduleNotFoundException($paymentScheduleId);
        }

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
        
        try {

            $paymentsEntitiesSaved = $this->paymentRecordRepository->save($paymentRecord);

            return $paymentsEntitiesSaved;

        } catch (Exception $e) {
            throw new DomainException('Error saving payment record');
        }*/
    }
    
}
