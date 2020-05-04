<?php

namespace Task\CommissionTask\Tests\Service;

use PHPUnit\Framework\TestCase;
use Task\CommissionTask\Service\CashOutClass;
use Task\CommissionTask\Service\HistoryClass;
use Task\CommissionTask\Service\PrintOutClass;

class cashOutClassTest extends TestCase
{
    public function testCountFeeLegal(): void
    {
        $print_out = $this->getMockBuilder(PrintOutClass::class)
            ->setMethods(['print'])
            ->getMock();
        $history = $this->getMockBuilder(HistoryClass::class)
            ->setMethods(['gerUserPayments'])
            ->getMock();

        $cash_in = new CashOutClass($history, $print_out);
        $data_legal = ['2016-01-06', '2', 'legal', 'cash_out', '300.00', 'EUR'];
        $print_out->expects($this->once())
            ->method('print')->with('EUR', 0.9);
        $cash_in->countFee($data_legal);
    }

    public function testCountFeeNatural(): void
    {
        $print_out = $this->getMockBuilder(PrintOutClass::class)
            ->setMethods(['print'])
            ->getMock();
        $history = $this->getMockBuilder(HistoryClass::class)
            ->setMethods(['gerUserPayments'])
            ->getMock();

        $cash_in = new CashOutClass($history, $print_out);
        $data_natural = ['2016-01-10', '1', 'natural', 'cash_out', '10000.00', 'EUR'];
        $history->expects($this->once())
            ->method('gerUserPayments')->with(1)->willReturn([]);
        $print_out->expects($this->once())
            ->method('print')->with('EUR', 27.0);
        $cash_in->countFee($data_natural);
    }
}
