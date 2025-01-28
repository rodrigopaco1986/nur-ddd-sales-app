<?php

namespace Tests\Unit\Order\Domain;

use DateTimeImmutable;
use Faker\Factory;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Src\Sales\Invoice\Domain\Entities\Invoice;
use Src\Sales\Invoice\Domain\ValueObject\InvoiceStatus;
use Src\Sales\Shared\Domain\ValueObject\Currency;

class InvoiceTest extends TestCase
{
    #[Test]
    public function itShouldCreateAnInvoice(): void
    {
        //Arrange
        $faker = Factory::create();
        $invoice = new Invoice(
            $faker->randomNumber(8, true),
            $faker->randomNumber(1, true),
            $faker->randomNumber(8, true),
            new DateTimeImmutable,
            Str::uuid(),
            $faker->randomNumber(5, true),
            $faker->sentence(3, true),
            $faker->randomNumber(8, true),
            new InvoiceStatus,
            new Currency,
            Str::uuid(),
        );

        //Act
        $isInstanceOfInvoice = $invoice instanceof Invoice;

        //Assert
        $this->assertTrue($isInstanceOfInvoice);
    }
}
