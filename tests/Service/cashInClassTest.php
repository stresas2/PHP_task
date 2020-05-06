<?php

declare(strict_types=1);

namespace Task\CommissionTask\Tests\Service;

use PHPUnit\Framework\TestCase;
use Task\CommissionTask\Service\CashInClass;
use Task\CommissionTask\Service\MoneyExchangeClass;

class cashInClassTest extends TestCase
{
    private $sut;

    private $mock;

    public function setUp(): void
    {
        $this->mock = $this->getMockBuilder(MoneyExchangeClass::class)->getMock();
        $this->sut = new CashInClass($this->mock);
    }

    public function testCountFee(): void
    {
        $pass_data = ['2016-01-10', '2', ' legal', 'cash_in', '1000000.00', 'EUR'];
        $this->mock->expects($this->once())->method('roundByCurrency')->with('EUR', 5.00)->willReturn(5.00);
        $this->expectOutputString(5.00);
        $this->sut->countFee($pass_data);
    }
}
