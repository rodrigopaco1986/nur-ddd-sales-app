<?php

namespace Src\Sales\Payment\Domain\Services;

use App\Exceptions\DomainException;
use Exception;
use Src\Sales\Payment\Domain\Entities\PaymentRecord;
use Src\Sales\Payment\Domain\Exceptions\PaymentRecordAlreadyCreatedException;
use Src\Sales\Payment\Domain\Exceptions\PaymentScheduleNotFoundException;
use Src\Sales\Payment\Domain\Repositories\PaymentRecordRepositoryInterface;
use Src\Sales\Payment\Domain\Repositories\PaymentScheduleRepositoryInterface;

class PaymentRecordDomainService
{
    private PaymentRecordRepositoryInterface $paymentRecordRepository;

    private PaymentScheduleRepositoryInterface $paymentScheduleRepository;

    public function __construct(
        PaymentRecordRepositoryInterface $paymentRecordRepository,
        PaymentScheduleRepositoryInterface $paymentScheduleRepository,
    ) {
        $this->paymentRecordRepository = $paymentRecordRepository;
        $this->paymentScheduleRepository = $paymentScheduleRepository;
    }

    public function create(string $paymentScheduleId): ?PaymentRecord
    {
        $paymentSchedule = $this->paymentScheduleRepository->findById($paymentScheduleId);
        $paymentRecord = $this->paymentRecordRepository->findByPaymentSchedule($paymentScheduleId);

        if (! $paymentSchedule) {
            throw new PaymentScheduleNotFoundException($paymentScheduleId);
        }

        if ($paymentRecord) {
            throw new PaymentRecordAlreadyCreatedException($paymentRecord->getPaymentScheduleId());
        }

        $paymentSchedule->markAsPaid();

        try {

            $this->paymentScheduleRepository->update($paymentSchedule);
            $paymentRecordEntity = $this->paymentRecordRepository->save($paymentSchedule);

            return $paymentRecordEntity;

        } catch (Exception $e) {
            throw new DomainException('Error saving payment record');
        }
    }
}
