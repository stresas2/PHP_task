<?php

namespace Task\CommissionTask\Service;

class PrintOutClass
{
    /**
     * @param string $currency
     * @param int $fee
     */
    public function print($currency, $fee): void
    {
        switch ($currency) {
            case PaymentClass::C_EUR:
            case PaymentClass::C_USD:
                echo number_format(ceil($fee * 100) / 100, 2) . PHP_EOL;
                break;
            case PaymentClass::C_JPY:
                $decimals = 2;
                if ($fee > 1000) {
                    $decimals = 0;
                }
                echo number_format(ceil($fee * 1) / 1, $decimals, ',', '') . PHP_EOL;
                break;
            case null:
                echo $fee . PHP_EOL;
                break;
        }
    }
}
