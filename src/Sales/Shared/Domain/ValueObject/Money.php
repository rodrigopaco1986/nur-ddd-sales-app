<?php

namespace Src\Sales\Shared\Domain\ValueObject;

class Money
{
    public function __construct(
        private readonly float $amount,
        private readonly Currency $currency,
    ) {}

    public function amount(): float
    {
        return $this->amount;
    }

    public function currency(): Currency
    {
        return $this->currency;
    }

    public function equals(Money $money)
    {
        return
            $money->currency()->equals($this->currency()) &&
            intval($money->amount()) == intval($this->amount()) &&
            floor($money->amount()) == floor($this->amount());
    }

    public static function fromMoney(Money $aMoney)
    {
        return new self(
            $aMoney->amount(),
            $aMoney->currency()
        );
    }

    public static function ofCurrency(Currency $aCurrency)
    {
        return new self(0, $aCurrency);
    }

    public function increaseAmountBy(int $anAmount)
    {
        return new self(
            $this->amount() + $anAmount,
            $this->currency()
        );
    }

    public function add(Money $money)
    {
        if ($money->currency() !== $this->currency()) {
            throw new \InvalidArgumentException;
        }

        return new self($this->amount() + $money->amount(), $this->currency());
    }

    public function sub(Money $money)
    {
        if ($money->currency() !== $this->currency()) {
            throw new \InvalidArgumentException;
        }

        return new self($this->amount() - $money->amount(), $this->currency());
    }
}
