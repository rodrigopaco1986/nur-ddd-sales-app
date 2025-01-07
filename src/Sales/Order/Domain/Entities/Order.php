<?php

namespace Src\Sales\Order\Domain\Entities;

use DateTimeImmutable;
use Src\Sales\Order\Domain\Exceptions\InvalidOrderItemException;
use Src\Sales\Order\Domain\ValueObject\OrderStatus;
use Src\Sales\Shared\Domain\ValueObject\Currency;

class Order
{
    private string $id;

    public function __construct(
        private string $patientId,
        private DateTimeImmutable $orderDate,
        private OrderStatus $status,
        private Currency $currency,
        private array $items = [],
    ) {
        foreach ($items as $item) {
            if (! $item instanceof OrderItem) {
                throw new InvalidOrderItemException;
            }
        }
    }

    /**
     * Get the value of id
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return self
     */
    public function setId(string $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of patientId
     */
    public function getPatientId(): string
    {
        return $this->patientId;
    }

    /**
     * Get the value of orderDate
     */
    public function getOrderDate(): DateTimeImmutable
    {
        return $this->orderDate;
    }

    /**
     * Get the value of status
     */
    public function getStatus(): string
    {
        return $this->status->getStatus();
    }

    public function getTotal(): float
    {
        return collect($this->items)->sum(function (OrderItem $orderItem) {
            return $orderItem->getSubTotal();
        });
    }

    /**
     * Get the value of currency
     */
    public function getCurrency(): string
    {
        return $this->currency->getIsoCode();
    }

    public function addItem(OrderItem $orderItem): void
    {
        $this->items[] = $orderItem;
    }

    public function getItems(): array
    {
        return $this->items;
    }
}
