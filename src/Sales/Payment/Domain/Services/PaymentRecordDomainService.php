<?php

namespace Src\Sales\Payment\Domain\Services;

use App\Exceptions\DomainException;
use Exception;
use Src\Sales\Payment\Application\Services\InvoiceService;
use Src\Sales\Payment\Domain\Entities\PaymentRecord;
use Src\Sales\Payment\Domain\Exceptions\InvoiceNotFoundException;
use Src\Sales\Payment\Domain\Exceptions\PaymentRecordAlreadyCreatedException;
use Src\Sales\Payment\Domain\Exceptions\PaymentScheduleNotFoundException;
use Src\Sales\Payment\Domain\Repositories\PaymentRecordRepositoryInterface;
use Src\Sales\Payment\Domain\Repositories\PaymentScheduleRepositoryInterface;

class PaymentRecordDomainService
{
    private PaymentRecordRepositoryInterface $paymentRecordRepository;

    private PaymentScheduleRepositoryInterface $paymentScheduleRepository;

    private InvoiceService $invoiceService;

    public function __construct(
        PaymentRecordRepositoryInterface $paymentRecordRepository,
        PaymentScheduleRepositoryInterface $paymentScheduleRepository,
        InvoiceService $invoiceService
    ) {
        $this->paymentRecordRepository = $paymentRecordRepository;
        $this->paymentScheduleRepository = $paymentScheduleRepository;
        $this->invoiceService = $invoiceService;
    }

    public function create(string $paymentScheduleId): ?PaymentRecord
    {
        $paymentSchedule = $this->paymentScheduleRepository->findById($paymentScheduleId);

        if (! $paymentSchedule) {
            throw new PaymentScheduleNotFoundException($paymentScheduleId);
        }

        $paymentRecord = $this->paymentRecordRepository->findByPaymentSchedule($paymentScheduleId);

        if ($paymentRecord) {
            throw new PaymentRecordAlreadyCreatedException($paymentRecord->getPaymentScheduleId());
        }

        $invoiceInfo = $this->invoiceService->getInvoiceInfo($paymentSchedule->getOrderId());

        if (! $invoiceInfo) {
            throw new InvoiceNotFoundException($paymentSchedule->getOrderId());
        }

        $paymentSchedule->markAsPaid();

        try {

            $this->paymentScheduleRepository->update($paymentSchedule);
            $paymentRecordEntity = $this->paymentRecordRepository->save($paymentSchedule, $invoiceInfo);

            return $paymentRecordEntity;

        } catch (Exception $e) {
            throw new DomainException('Error saving payment record');
        }
    }
}
