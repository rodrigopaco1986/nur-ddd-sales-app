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
    public function itShouldCreateAnOrderItem(): void
    {
        //Arrange
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

        //Act
        $isInstanceOfOrder = $orderItem instanceof OrderItem;

        //Assert
        $this->assertTrue($isInstanceOfOrder);
    }

    #[Test]
    public function itShouldThrowAnExceptionWhenServiiceIdIsNotValid(): void
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
    public function itShouldThrowAnExceptionWhenQuantityIsNotValid(): void
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
    public function itShouldThrowAnExceptionWhenPriceIsNotValid(): void
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
    public function itShouldThrowAnExceptionWhenDiscountIsNotValid(): void
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
    public function itShouldThrowAnExceptionWhenPriceIsLowerThanDiscount(): void
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
