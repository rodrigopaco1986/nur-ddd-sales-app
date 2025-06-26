<?php

namespace Src\Sales\Payment\Domain\ValueObject;

use Src\Sales\Payment\Domain\Exceptions\ValueException;

class PaymentStatus
{
    private const PENDING = 'PENDING';

    private const PAID = 'PAID';

    private string $status;

    public function __construct(string $status = 'PENDING')
    {
        if (! in_array($status, [self::PENDING, self::PAID])) {
            throw new ValueException('Invalid invoice status');
        }
        $this->status = $status;
    }

    public static function PENDING(): self
    {
        return new self(self::PENDING);
    }

    public static function PAID(): self
    {
        return new self(self::PAID);
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
