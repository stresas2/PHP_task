<?php

namespace Task\CommissionTask\Tests\Service;

use PHPUnit\Framework\TestCase;
use Task\CommissionTask\Service\HistoryClass;

class HistoryClassTest extends TestCase
{
    public function testGerUserPayments(): void
    {
        $history = new HistoryClass();
        for ($i = 0; $i <= 2; $i++) {
            $history->setPayment(['2016-01-10', '1', 'natural', 'cash_out', '100.00', 'EUR']);
        }

        $expected = [['2016-01-10', '1', 'natural', 'cash_out', '100.00', 'EUR'], ['2016-01-10', '1', 'natural', 'cash_out', '100.00', 'EUR'], ['2016-01-10', '1', 'natural', 'cash_out', '100.00', 'EUR']];
        $actual = $history->gerUserPayments('1');
        $this->assertSame($expected, $actual);
    }
}
