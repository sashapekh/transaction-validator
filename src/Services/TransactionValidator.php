<?php

namespace Sashapekh\TransactionValidator\Services;

use Sashapekh\TransactionValidator\CurrencyHelper;
use Sashapekh\TransactionValidator\Dto\BankDto;
use Sashapekh\TransactionValidator\Dto\ClientDto;
use Sashapekh\TransactionValidator\Dto\PrecisionRange;

class TransactionValidator
{
    private const int DEFAULT_PRECISION_PERCET = 10;
    private PrecisionRange $precisionRange;

    public function __construct(private float|int $precisionPercent = self::DEFAULT_PRECISION_PERCET)
    {
        $this->checkPrecisionPercent();
    }

    public function validateTransaction(ClientDTO $clientDTO, BankDTO $bankDTO): bool
    {
        if (!in_array($clientDTO->currency, CurrencyHelper::getAllowedCurrencies()) || !in_array($bankDTO->currency,
                CurrencyHelper::getAllowedCurrencies())) {
            return false;
        }

        $this->precisionRange = $this->getPrecisionRange($bankDTO);


        return $clientDTO->currency === $bankDTO->currency &&
            (
                bccomp(
                    (string) $clientDTO->sum, $this->precisionRange->minAllowedSum, 2) >= 0
                &&
                bccomp(
                    (string) $clientDTO->sum, $this->precisionRange->maxAllowedSum, 2) <= 0
            );
    }


    private function getPrecisionRange(BankDto $dto): PrecisionRange
    {
        if ($this->precisionPercent === 0) {
            return new PrecisionRange((string) $dto->sum, (string) $dto->sum);
        }

        $min = (100 - $this->precisionPercent) / 100;
        $max = (100 + $this->precisionPercent) / 100;

        return new PrecisionRange(
            bcmul((string) $dto->sum, $min, 2),
            bcmul((string) $dto->sum, $max, 2)
        );
    }

    private function checkPrecisionPercent(): void
    {
        if ($this->precisionPercent < 0 || $this->precisionPercent > 100) {
            throw new \InvalidArgumentException('Precision percent should be between 0 and 100 inclusive.');
        }
    }
}