<?php

namespace Tests\Unit\Order\Domain;

use DateTimeImmutable;
use Faker\Factory;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Src\Sales\Invoice\Domain\Entities\InvoiceItem;
use Src\Sales\Order\Domain\Exceptions\ValueException;
use Src\Sales\Shared\Domain\ValueObject\Currency;
use Src\Sales\Shared\Domain\ValueObject\Money;

class InvoiceItemTest extends TestCase
{
    #[Test]
    public function itShouldCreateAnInvoiceItem(): void
    {
        //Arrange
        $faker = Factory::create();
        $invoiceItem = new InvoiceItem(
            Str::uuid(),
            $faker->randomNumber(5, true),
            $faker->sentence(3),
            $faker->word(),
            $faker->randomNumber(2, true),
            new Money($faker->randomFloat(2, 10, 100), new Currency),
            new Money($faker->randomFloat(1, 0, 10), new Currency),
        );

        //Act
        $isInstanceOfInvoiceItem = $invoiceItem instanceof InvoiceItem;

        //Assert
        $this->assertTrue($isInstanceOfInvoiceItem);
    }
}
