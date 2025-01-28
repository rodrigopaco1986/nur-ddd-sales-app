<?php

namespace Tests\Unit\Order\Domain;

use DateTimeImmutable;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Src\Sales\Order\Domain\Entities\Order;
use Src\Sales\Order\Domain\Exceptions\InvalidOrderItemException;
use Src\Sales\Order\Domain\Exceptions\ValueException;
use Src\Sales\Order\Domain\ValueObject\OrderStatus;
use Src\Sales\Shared\Domain\ValueObject\Currency;

class OrderTest extends TestCase
{
    #[Test]
    public function itShouldCreateAnOrder(): void
    {
        //Arrange
        $order = new Order(
            Str::uuid(),
            new DateTimeImmutable,
            new OrderStatus,
            new Currency,
        );

        //Act
        $isInstanceOfOrder = $order instanceof Order;

        //Assert
        $this->assertTrue($isInstanceOfOrder);
    }

    #[Test]
    public function itShouldThrowAnExceptionWhenPatiendIdIsNotValid(): void
    {
        $this->expectException(ValueException::class);

        new Order(
            '',
            new DateTimeImmutable,
            new OrderStatus,
            new Currency,
        );
    }

    #[Test]
    public function itShouldThrowAnExceptionWhenOrderItemIsNotValid(): void
    {
        $this->expectException(InvalidOrderItemException::class);

        new Order(
            Str::uuid(),
            new DateTimeImmutable,
            new OrderStatus,
            new Currency,
            [
                'invalid-item',
            ]
        );
    }
}
