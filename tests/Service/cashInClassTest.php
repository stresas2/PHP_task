<?php

declare(strict_types=1);

namespace Task\CommissionTask\Tests\Service;

use PHPUnit\Framework\TestCase;
use Task\CommissionTask\Service\CashInClass;
use Task\CommissionTask\Service\MoneyExchangeClass;

class cashInClassTest extends TestCase
{
    // Fake NumberFormatter class
    // Testing class - MoneyFormatter

    private $sut;

    private $mock

    public function setUp(): void
    {
        // Set Fake Class
        $this->mock = $this->getMockBuilder(MoneyExchangeClass::class)->getMock();

        // Set Testing class
        $this->sut = new CashInClass($this->mock);
    }

    public function testCountFee(): void
    {
        $pass_data = ['2016-01-10', '2', ' legal', 'cash_in', '1000000.00', 'EUR'];
        $this->sut->countFee($pass_data);
//        $print_out = $this->getMockBuilder(PrintOutClass::class)
//            ->setMethods(['print'])
//            ->getMock();
//
//        $print_out->expects($this->once())
//            ->method('print')->with('EUR', 5.00);
//
//        $cash_in = new CashInClass($print_out);
//
//        $pass_data = ['2016-01-10', '2', ' legal', 'cash_in', '1000000.00', 'EUR'];
//        $cash_in->countFee($pass_data);
    }
}
