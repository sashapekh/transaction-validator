<?php

require __DIR__.'/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Sashapekh\TransactionValidator\Dto\BankDto;
use Sashapekh\TransactionValidator\Dto\ClientDto;
use Sashapekh\TransactionValidator\Services\TransactionValidator;

class TransactionValidatorTest extends TestCase
{
    public function testValidateTransactionWithinDeviation()
    {
        $clientDTO = new ClientDTO(sum: '90', currency: 'USD');
        $bankDTO = new BankDTO(sum: '100', currency: 'USD');
        $this->assertTrue((new TransactionValidator())->validateTransaction($clientDTO, $bankDTO));

        $clientDTO = new ClientDTO(sum: '110', currency: 'USD');
        $this->assertTrue((new TransactionValidator())->validateTransaction($clientDTO, $bankDTO));
    }

    public function testValidateTransactionOutsideDeviation()
    {
        $clientDTO = new ClientDTO(sum: '89', currency: 'USD');
        $bankDTO = new BankDTO(sum: '100', currency: 'USD');
        $this->assertFalse((new TransactionValidator())->validateTransaction($clientDTO, $bankDTO));

        $clientDTO = new ClientDTO(sum: '111', currency: 'USD');
        $this->assertFalse((new TransactionValidator())->validateTransaction($clientDTO, $bankDTO));
    }

    public function testValidateTransactionDifferentCurrency()
    {
        $clientDTO = new ClientDTO(sum: '100', currency: 'EUR');
        $bankDTO = new BankDTO(sum: '100', currency: 'USD');
        $this->assertFalse((new TransactionValidator())->validateTransaction($clientDTO, $bankDTO));
    }

    public function testValidateTransactionInvalidCurrency()
    {
        $clientDTO = new ClientDTO(sum: '100', currency: 'UAH');
        $bankDTO = new BankDTO(sum: '100', currency: 'USD');
        $this->assertFalse((new TransactionValidator())->validateTransaction($clientDTO, $bankDTO));
    }

    public function testValidateTransactionInvalidCurrency2()
    {
        $clientDTO = new ClientDTO(sum: '100', currency: 'USD');
        $bankDTO = new BankDTO(sum: '100', currency: 'UAH');
        $this->assertFalse((new TransactionValidator())->validateTransaction($clientDTO, $bankDTO));
    }


    public function testValidateTransactionInvalidCurrency3()
    {
        $clientDTO = new ClientDTO(sum: '100', currency: 'UAH');
        $bankDTO = new BankDTO(sum: '100', currency: 'UAH');
        $this->assertTrue((new TransactionValidator())->validateTransaction($clientDTO, $bankDTO));
    }

    // test with int and float sum
    public function testValidateTransactionWithIntSum()
    {
        $clientDTO = new ClientDTO(sum: 100, currency: 'USD');
        $bankDTO = new BankDTO(sum: 100, currency: 'USD');
        $this->assertTrue((new TransactionValidator())->validateTransaction($clientDTO, $bankDTO));
    }

    public function testValidateTransactionWithIntSum2()
    {
        $clientDTO = new ClientDTO(sum: 100, currency: 'USD');
        $bankDTO = new BankDTO(sum: 1, currency: 'USD');
        $this->assertFalse((new TransactionValidator())->validateTransaction($clientDTO, $bankDTO));
    }

    public function testValidateTransactionWithFloatSum()
    {
        $clientDTO = new ClientDTO(sum: 100.5, currency: 'USD');
        $bankDTO = new BankDTO(sum: 100.5, currency: 'USD');
        $this->assertTrue((new TransactionValidator())->validateTransaction($clientDTO, $bankDTO));
    }

    public function testValidateTransactionWithFloatSum2()
    {
        $clientDTO = new ClientDTO(sum: 10.5, currency: 'USD');
        $bankDTO = new BankDTO(sum: 100.5, currency: 'USD');
        $this->assertFalse((new TransactionValidator())->validateTransaction($clientDTO, $bankDTO));
    }
}