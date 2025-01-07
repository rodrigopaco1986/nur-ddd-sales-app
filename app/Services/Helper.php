<?php

namespace App\Services;

use App\Services\Helper as H;
use NumberFormatter;
use Src\Sales\Shared\Domain\ValueObject\Currency;
use Src\Sales\Shared\Domain\ValueObject\Money;

class Helper
{
    public static function getCurrencySymbol(string $locale, string $currencyCode): string
    {
        $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);

        return $formatter->getSymbol(NumberFormatter::CURRENCY_SYMBOL, $currencyCode);
    }

    public static function getCurrencyCodeFromLocale(string $locale): string
    {
        $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);

        return $formatter->getTextAttribute(NumberFormatter::CURRENCY_CODE);
    }

    public static function getDefaultCurrencyCode(): string
    {
        return Helper::getCurrencyCodeFromLocale(config('app.locale'));
    }

    public static function parseMoneyWithDefaultCurrency(float $value): Money
    {
        return new Money($value, new Currency(H::getDefaultCurrencyCode()));
    }
}
