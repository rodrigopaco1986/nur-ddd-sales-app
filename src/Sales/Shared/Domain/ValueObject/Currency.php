<?php

namespace Src\Sales\Shared\Domain\ValueObject;

class Currency
{
    public function __construct(private readonly string $isoCode = 'BOB')
    {
        if (! preg_match('/^[A-Z]{3}$/', $isoCode)) {
            throw new \InvalidArgumentException;
        }
    }

    public function getIsoCode(): string
    {
        return $this->isoCode;
    }

    public function equals(Currency $currency): bool
    {
        return $currency->getIsoCode() === $this->getIsoCode();
    }
}
