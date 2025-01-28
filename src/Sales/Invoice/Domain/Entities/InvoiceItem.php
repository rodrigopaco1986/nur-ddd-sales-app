<?php

namespace Src\Sales\Invoice\Domain\Entities;

use Exception;
use Src\Sales\Shared\Domain\ValueObject\Money;
use Src\Sales\Invoice\Domain\Exceptions\ValueException;
use Illuminate\Support\Str;

class InvoiceItem
{
    private string $id;

    public function __construct(
        private string $serviceId,
        private string $serviceCode,
        private string $serviceName,
        private string $serviceUnit,
        private int $quantity,
        private Money $price,
        private Money $discount,
    ) {
        if (! Str::isUuid($serviceId)) {
            throw new ValueException('Invalid service id');
        }

        if ($this->quantity < 0) {
            throw new Exception('Invoice Item: Quantity cant be lower than zero for '.$this->serviceId);
        }

        if ($this->price->amount() < 0) {
            throw new Exception('Invoice Item: Price cant be lower than zero for '.$this->serviceId);
        }

        if ($this->discount->amount() < 0) {
            throw new Exception('Invoice Item: Discount cant be lower than zero for '.$this->serviceId);
        }

        if ($this->price->amount() * $this->quantity < $this->discount->amount()) {
            throw new Exception('Invoice Item: Discount can\'t be lower than subtotal for '.$this->serviceId);
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
     */
    public function setId($id): InvoiceItem
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of serviceId
     */
    public function getServiceId(): string
    {
        return $this->serviceId;
    }

    /**
     * Get the value of serviceCode
     */
    public function getServiceCode(): string
    {
        return $this->serviceCode;
    }

    /**
     * Get the value of serviceName
     */
    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    /**
     * Get the value of serviceUnit
     */
    public function getServiceUnit(): string
    {
        return $this->serviceUnit;
    }

    /**
     * Get the value of quantity
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * Get the value of price
     */
    public function getPrice(): float
    {
        return $this->price->amount();
    }

    /**
     * Get the value of discount
     */
    public function getDiscount(): float
    {
        return $this->discount->amount();
    }

    public function getSubTotal(): float
    {
        return ($this->quantity * $this->price->amount()) - $this->discount->amount();
    }
}
