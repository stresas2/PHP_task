<?php

declare(strict_types=1);

namespace Task\CommissionTask\Service;

class CashInClass
{
    private const CommissionFeePercent = 0.03;
    private const MaxFee = 5.00;

    /**
     * @var MoneyExchangeClass
     */
    private $moneyExchanger;

    public function __construct(MoneyExchangeClass $money_exchanger)
    {
        $this->moneyExchanger = $money_exchanger;
    }

    /**
     * @param array $payment
     */
    public function countFee(array $payment): void
    {
        [, , , , $amount, $currency] = $payment;

        $fee = $amount * (self::CommissionFeePercent / 100);
        if ($currency === 'EUR') {
            if ($fee > self::MaxFee) {
                $fee_rounded = $this->moneyExchanger->roundByCurrency($currency, self::MaxFee);
            } else {
                $fee_rounded = $this->moneyExchanger->roundByCurrency($currency, $fee);
            }

            echo $fee_rounded;
            return;
        }

        $fee_in_eur = $this->moneyExchanger->convertToEur($currency, $fee);
        if ($fee_in_eur > self::MaxFee) {
            $fee = $this->moneyExchanger->exchangeToOriginalCurrency(self::MaxFee, $currency);
            $fee_rounded = $this->moneyExchanger->roundByCurrency($currency, $fee);
        } else {
            $fee_rounded = $this->moneyExchanger->roundByCurrency($currency, $fee);
        }

        echo $fee_rounded;
    }
}
