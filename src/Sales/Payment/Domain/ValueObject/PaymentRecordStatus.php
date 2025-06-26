<?php

namespace Src\Sales\Payment\Domain\ValueObject;

use Src\Sales\Payment\Domain\Exceptions\ValueException;

class PaymentRecordStatus
{
    private const CREATED = 'CREATED';

    private const DELIVERED = 'DELIVERED';

    private string $status;

    public function __construct(string $status = 'CREATED')
    {
        if (! in_array($status, [self::CREATED, self::DELIVERED])) {
            throw new ValueException('Invalid payment record status');
        }
        $this->status = $status;
    }

    public static function CREATED(): self
    {
        return new self(self::CREATED);
    }

    public static function DELIVERED(): self
    {
        return new self(self::DELIVERED);
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
