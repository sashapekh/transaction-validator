<?php

namespace Sashapekh\TransactionValidator;

class CurrencyHelper
{
    public const USD = 'USD';
    public const EUR = 'EUR';
    public const UAH = 'UAH';

    public static function getAllowedCurrencies(): array
    {
        return [self::USD, self::EUR, self::UAH];
    }
}