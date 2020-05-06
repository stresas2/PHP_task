<?php

namespace Task\CommissionTask\Tests\Service;

use PHPUnit\Framework\TestCase;
use Task\CommissionTask\Service\CashOutClass;
use Task\CommissionTask\Service\HistoryClass;
use Task\CommissionTask\Service\MoneyExchangeClass;

class cashOutClassTest extends TestCase
{
    private $sut;

    private $mock_money_exchange;

    private $mock_history;

    public function setUp(): void
    {
        $this->mock_money_exchange = $this->getMockBuilder(MoneyExchangeClass::class)->getMock();
        $this->mock_history = $this->getMockBuilder(HistoryClass::class)->getMock();
        $this->sut = new CashOutClass($this->mock_history, $this->mock_money_exchange);
    }

    public function testCountFeeLegal(): void
    {
        $data_legal = ['2016-01-06', '2', 'legal', 'cash_out', '300.00', 'EUR'];
        $this->mock_money_exchange->expects($this->once())
            ->method('roundByCurrency')->with('EUR', 0.9)->willReturn(0.90);
        $this->sut->countFeeByUser($data_legal);

        $this->expectOutputString(0.90);
    }

    public function testCountFeeNatural(): void
    {
        $data_natural = ['2016-01-10', '1', 'natural', 'cash_out', '10000.00', 'EUR'];
        $this->mock_history->expects($this->once())
            ->method('gerUserPayments')->with(1)->willReturn([]);
        $this->sut->countFeeByUser($data_natural);

        $this->expectOutputString(27.00);
    }
}
