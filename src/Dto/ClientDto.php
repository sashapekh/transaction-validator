<?php

namespace Sashapekh\TransactionValidator\Dto;

readonly class ClientDto
{
    public function __construct(
        public float|string|int $sum,
        public string $currency
    ) {
    }
}