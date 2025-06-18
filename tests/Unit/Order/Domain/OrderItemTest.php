<?php

namespace Tests\Unit\Order\Domain;

use Faker\Factory;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Src\Sales\Order\Domain\Entities\OrderItem;
use Src\Sales\Order\Domain\Exceptions\ValueException;
use Src\Sales\Shared\Domain\ValueObject\Currency;
use Src\Sales\Shared\Domain\ValueObject\Money;

class OrderItemTest extends TestCase
{
    #[Test]
    public function it_should_create_an_order_item(): void
    {
        // Arrange
        $faker = Factory::create();
        $orderItem = new OrderItem(
            Str::uuid(),
            $faker->randomNumber(5, true),
            $faker->sentence(3),
            $faker->word(),
            $faker->randomNumber(2, true),
            new Money($faker->randomFloat(2, 10, 100), new Currency),
            new Money($faker->randomFloat(1, 0, 10), new Currency),
        );

        // Act
        $isInstanceOfOrder = $orderItem instanceof OrderItem;

        // Assert
        $this->assertTrue($isInstanceOfOrder);
    }

    #[Test]
    public function it_should_throw_an_exception_when_serviice_id_is_not_valid(): void
    {
        $this->expectException(ValueException::class);

        $faker = Factory::create();
        new OrderItem(
            '',
            $faker->randomNumber(5, true),
            $faker->sentence(3),
            $faker->word(),
            $faker->randomNumber(2, true),
            new Money($faker->randomFloat(2, 10, 100), new Currency),
            new Money($faker->randomFloat(1, 0, 10), new Currency),
        );
    }

    #[Test]
    public function it_should_throw_an_exception_when_quantity_is_not_valid(): void
    {
        $this->expectException(ValueException::class);

        $faker = Factory::create();
        new OrderItem(
            Str::uuid(),
            $faker->randomNumber(5, true),
            $faker->sentence(3),
            $faker->word(),
            0,
            new Money($faker->randomFloat(2, 10, 100), new Currency),
            new Money($faker->randomFloat(1, 0, 10), new Currency),
        );
    }

    #[Test]
    public function it_should_throw_an_exception_when_price_is_not_valid(): void
    {
        $this->expectException(ValueException::class);

        $faker = Factory::create();
        new OrderItem(
            Str::uuid(),
            $faker->randomNumber(5, true),
            $faker->sentence(3),
            $faker->word(),
            $faker->randomNumber(2, true),
            new Money(0, new Currency),
            new Money($faker->randomFloat(1, 0, 10), new Currency),
        );
    }

    #[Test]
    public function it_should_throw_an_exception_when_discount_is_not_valid(): void
    {
        $this->expectException(ValueException::class);

        $faker = Factory::create();
        new OrderItem(
            Str::uuid(),
            $faker->randomNumber(5, true),
            $faker->sentence(3),
            $faker->word(),
            $faker->randomNumber(2, true),
            new Money($faker->randomFloat(2, 10, 100), new Currency),
            new Money(-1, new Currency),
        );
    }

    #[Test]
    public function it_should_throw_an_exception_when_price_is_lower_than_discount(): void
    {
        $this->expectException(ValueException::class);

        $faker = Factory::create();
        new OrderItem(
            Str::uuid(),
            $faker->randomNumber(5, true),
            $faker->sentence(3),
            $faker->word(),
            1,
            new Money(10, new Currency),
            new Money(100, new Currency),
        );
    }
}
