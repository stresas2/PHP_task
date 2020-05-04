<?php

declare(strict_types=1);

namespace Task\CommissionTask\Tests\Service;

use PHPUnit\Framework\TestCase;
use Task\CommissionTask\Service\CashInClass;
use Task\CommissionTask\Service\PrintOutClass;

class cashInClassTest extends TestCase
{
    public function testCountFee(): void
    {
        $print_out = $this->getMockBuilder(PrintOutClass::class)
            ->setMethods(['print'])
            ->getMock();

        $print_out->expects($this->once())
            ->method('print')->with('EUR', 5.00);

        $cash_in = new CashInClass($print_out);

        $pass_data = ['2016-01-10', '2', ' legal', 'cash_in', '1000000.00', 'EUR'];
        $cash_in->countFee($pass_data);
    }
}
