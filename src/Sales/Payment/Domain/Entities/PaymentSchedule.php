<?php

namespace Src\Sales\Payment\Domain\Entities;

use DateTimeImmutable;
use Src\Sales\Payment\Domain\Exceptions\ValueException;
use Src\Sales\Payment\Domain\ValueObject\PaymentStatus;
use Src\Sales\Shared\Domain\ValueObject\Money;

class PaymentSchedule
{
    private string $id;

    public function __construct(
        private int $number,
        private Money $amount,
        private DateTimeImmutable $dueDate,
        private PaymentStatus $status,
        private string $orderId,
        private ?PaymentRecord $paymentRecord = null,
    ) {}

    /**
     * Get the value of id
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId($id): PaymentSchedule
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of number
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * Get the value of dueDate
     */
    public function getDueDate(): DateTimeImmutable
    {
        return $this->dueDate;
    }

    /**
     * Get the value of amount
     */
    public function getAmount(): float
    {
        return $this->amount->amount();
    }

    /**
     * Get the value of status
     */
    public function getStatus(): string
    {
        return $this->status->getStatus();
    }

    /**
     * Get the value of orderId
     */
    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * Get the value of currency
     */
    public function getCurrency(): string
    {
        return $this->amount->currency()->getIsoCode();
    }

    /**
     * Get the value of payment record
     */
    public function getPaymentRecord(): ?PaymentRecord
    {
        return $this->paymentRecord;
    }

    public function markAsPaid(): void
    {
        if ($this->getStatus() == PaymentStatus::PENDING()->getStatus()) {
            $this->status = PaymentStatus::PAID();

            return;
        }

        throw new ValueException('Payment schedule is not in pending status!');
    }
}
