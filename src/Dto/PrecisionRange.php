<?php

namespace Sashapekh\TransactionValidator\Dto;

readonly class PrecisionRange
{
    public function __construct(
        public float $minAllowedSum,
        public float $maxAllowedSum
    ) {
    }
}