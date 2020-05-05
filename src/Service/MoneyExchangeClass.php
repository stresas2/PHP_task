<?php

namespace Task\CommissionTask\Service;

class MoneyExchangeClass
{
    private $currencies = [];

    public function __construct()
    {
        $this->currencies[] = ['EUR', 1, 2];
        $this->currencies[] = ['USD', 1.1497, 2];
        $this->currencies[] = ['JPY', 129.53, 0];
    }

    /**
     * @param string $currency
     * @param float $fee
     * @return float
     */
    public function convertToEur(string $currency, float $fee): float
    {
        foreach ($this->currencies as $currency_data)
        {
            if($currency_data[0] === $currency){
                return $fee / $currency_data[1];
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
        foreach ($week_amounts as $amount) {
            foreach ($this->currencies as $currency_data)
            {
                if($currency_data[0] === $amount[0]){
                    $total_amount += $amount[1] / $currency_data[1];
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
        foreach ($this->currencies as $currency_data)
        {
            if($currency_data[0] === $currency){
                return $fee_in_eur * $currency_data[1];
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
        foreach ($this->currencies as $currency_data)
        {
            $ceil = 10 ** $currency_data[2];
            if($currency_data[0] === $currency){
                return number_format(ceil($amount * $ceil) / $ceil, $currency_data[2], '.', '') . PHP_EOL;
            }
        }
    }


}