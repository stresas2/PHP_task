<?php

namespace Task\CommissionTask\Service;

class MoneyExchangeClass
{
    public const CURRENCIES = [
        'EUR' => [1, 2],
        'USD' => [1.1497, 2],
        'JPY' => [129.53, 0]
    ];

    /**
     * @param string $currency
     * @param float $fee
     * @return float
     */
    public function convertToEur(string $currency, float $fee): float
    {
        foreach (self::CURRENCIES as $currency_name => $currency_data) {
            if ($currency_name === $currency) {
                return $fee / $currency_data[0];
            }
        }
    }

    /**
     * @param array $week_amounts
     * @return float
     */
    public function totalAmountInEur(array $week_amounts): float
    {
        $total_amount = 0;
        foreach ($week_amounts as $payment) {
            foreach (self::CURRENCIES as $currency_name => $currency_data) {
                if ($currency_name === $payment[0]) {
                    $total_amount += $payment[1] / $currency_data[0];
                }
            }
        }

        return $total_amount;
    }

    /**
     * @param float $fee_in_eur
     * @param string $currency
     * @return float
     */
    public function exchangeToOriginalCurrency(float $fee_in_eur, string $currency): float
    {
        foreach (self::CURRENCIES as $currency_name => $currency_data) {
            if ($currency_name === $currency) {
                return $fee_in_eur * $currency_data[0];
            }
        }
    }

    /**
     * @param string $currency
     * @param float $amount
     * @return string
     */
    public function roundByCurrency(string $currency, float $amount): string
    {
        foreach (self::CURRENCIES as $currency_name => $currency_data) {
            $ceil = 10 ** $currency_data[1];
            if ($currency_name === $currency) {
                return number_format(ceil($amount * $ceil) / $ceil, $currency_data[1], '.', '') . PHP_EOL;
            }
        }
    }
}
