<?php

namespace Task\CommissionTask\Tests\Service;

use PHPUnit\Framework\TestCase;
use Task\CommissionTask\Service\HistoryClass;

class HistoryClassTest extends TestCase
{
    private $sut;

    public function setUp(): void
    {
        $this->sut = new HistoryClass();
    }
    public function testGerUserPayments(): void
    {
        for ($i = 0; $i <= 2; $i++) {
            $this->sut->setPayment(['2016-01-10', '1', 'natural', 'cash_out', '100.00', 'EUR']);
        }

        $expected = [['2016-01-10', '1', 'natural', 'cash_out', '100.00', 'EUR'], ['2016-01-10', '1', 'natural', 'cash_out', '100.00', 'EUR'], ['2016-01-10', '1', 'natural', 'cash_out', '100.00', 'EUR']];
        $actual = $this->sut->gerUserPayments('1');
        $this->assertSame($expected, $actual);
    }
}
