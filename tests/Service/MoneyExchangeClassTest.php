<?php

namespace Task\CommissionTask\Tests\Service;

use PHPUnit\Framework\TestCase;
use Task\CommissionTask\Service\MoneyExchangeClass;

class MoneyExchangeClassTest extends TestCase
{
    private $sut;

    public function setUp(): void
    {
        $this->sut = new MoneyExchangeClass();
    }

    public function testConvertToEur(): void
    {
        $result = $this->sut->convertToEur('JPY', 259.06);
        $this->assertSame(2.0, $result);
    }

    public function testTotalAmountInEur(): void
    {
        $result = $this->sut->totalAmountInEur([['JPY', 259.06], ['EUR', 5]]);
        $this->assertSame(7.0, $result);
    }

    public function testExchangeToOriginalCurrency(): void
    {
        $result = $this->sut->exchangeToOriginalCurrency(1, 'USD');
        $this->assertSame(1.1497, $result);
    }

    public function testRoundByCurrency(): void
    {
        $result = $this->sut->roundByCurrency('JPY', 188.88);
        $this->assertSame(189.0 . PHP_EOL, $result);
    }
}
