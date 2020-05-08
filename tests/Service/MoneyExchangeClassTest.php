<?php

namespace Task\CommissionTask\Tests\Service;

use PHPUnit\Framework\TestCase;
use Task\CommissionTask\Service\MoneyExchangeClass;

class MoneyExchangeClassTest extends TestCase
{
    private $sut;

    private $currencies;

    public function setUp(): void
    {
        $this->sut = new MoneyExchangeClass();
        $this->currencies = MoneyExchangeClass::CURRENCIES;
    }

    public function testConvertToEur(): void
    {
        $test_input = ['JPY', 259.06];
        $currency = $this->currencies[$test_input[0]];
        $expected = $test_input[1] / $currency[0];
        $result = $this->sut->convertToEur($test_input[0], $test_input[1]);
        $this->assertSame($expected, $result);
    }

    public function testTotalAmountInEur(): void
    {
        $test_input = [['JPY', 500], ['USD', 2], ['EUR', 5]];
        $total_amount = 0;
        foreach ($test_input as $payment) {
            foreach ($this->currencies as $currency_name => $currency_data) {
                if ($currency_name === $payment[0]) {
                    $total_amount += $payment[1] / $currency_data[0];
                }
            }
        }

        $result = $this->sut->totalAmountInEur($test_input);
        $this->assertSame($total_amount, $result);
    }

    public function testExchangeToOriginalCurrency(): void
    {
        $test_input = ['USD', 8];
        $currency = $this->currencies[$test_input[0]];
        $expected = $test_input[1] * $currency[0];
        $result = $this->sut->exchangeToOriginalCurrency($test_input[1], $test_input[0]);
        $this->assertSame($expected, $result);
    }

    public function testRoundByCurrency(): void
    {
        $test_input = ['JPY', 500];
        $currency = $this->currencies[$test_input[0]];
        $ceil = 10 ** $currency[1];
        $expected = number_format(ceil($test_input[1] * $ceil) / $ceil, $currency[1], '.', '') . PHP_EOL;
        $result = $this->sut->roundByCurrency($test_input[0], $test_input[1]);
        $this->assertSame($expected, $result);
    }
}
