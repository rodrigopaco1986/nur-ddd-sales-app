<?php

namespace Src\Sales\Payment\Domain\Entities;

use DateTimeImmutable;
use Src\Sales\Payment\Domain\ValueObject\PaymentRecordStatus;
use Src\Sales\Shared\Domain\ValueObject\Money;

class PaymentRecord
{
    private string $id;

    public function __construct(
        private Money $amount,
        private DateTimeImmutable $payedDate,
        private PaymentRecordStatus $status,
        private string $firstName,
        private string $lastName,
        private string $dni,
        private string $orderId,
        private string $paymentScheduleId,
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
    public function setId($id): PaymentRecord
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of amount
     */
    public function getAmount(): float
    {
        return $this->amount->amount();
    }

    /**
     * Get the value of currency
     */
    public function getCurrency(): string
    {
        return $this->amount->currency()->getIsoCode();
    }

    /**
     * Get the value of payedDate
     */
    public function getPayedDate(): DateTimeImmutable
    {
        return $this->payedDate;
    }

    /**
     * Get the value of status
     */
    public function getStatus(): string
    {
        return $this->status->getStatus();
    }

    /**
     * Get the value of firstName
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * Get the value of lastName
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * Get the value of dni
     */
    public function getDni(): string
    {
        return $this->dni;
    }

    /**
     * Get the value of orderId
     */
    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * Get the value of paymentScheduleId
     */
    public function getPaymentScheduleId(): string
    {
        return $this->paymentScheduleId;
    }
}
