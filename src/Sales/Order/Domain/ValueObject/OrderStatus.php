<?php

namespace Src\Sales\Order\Domain\ValueObject;

use Src\Sales\Order\Domain\Exceptions\ValueException;

class OrderStatus
{
    private const CREATED = 'CREATED';

    private const PENDING = 'PENDING';

    private const PAID = 'PAID';

    private const CANCELLED = 'CANCELLED';

    private string $status;

    public function __construct(string $status = 'CREATED')
    {
        if (! in_array($status, [self::CREATED, self::PENDING, self::PAID, self::CANCELLED])
        ) {
            throw new ValueException('Invalid status');
        }
        $this->status = $status;
    }

    public static function CREATED(): self
    {
        return new self(self::CREATED);
    }

    public static function PENDING(): self
    {
        return new self(self::PENDING);
    }

    public static function PAID(): self
    {
        return new self(self::PAID);
    }

    public static function CANCELLED(): self
    {
        return new self(self::CANCELLED);
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
