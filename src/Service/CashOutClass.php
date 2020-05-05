<?php

declare(strict_types=1);

namespace Task\CommissionTask\Service;

class CashOutClass
{
    private const userLegal = 'legal';
    private const userNatural = 'natural';
    private const legalFeePercent = 0.3;
    private const naturalFeePercent = 0.3;
    private const naturalFreeCharge = 1000.00;
    private const naturalFreeTimes = 3;
    private const legalMinFee = 0.50;

    /**
     * @var HistoryClass
     */
    private $history;

    /**
     * @var MoneyExchangeClass
     */
    private $moneyExchanger;

    public function __construct(HistoryClass $history_all, MoneyExchangeClass $money_exchanger)
    {
        $this->history = $history_all;
        $this->moneyExchanger = $money_exchanger;
    }

    /**
     * @param array $payment
     */
    public function countFeeByUser(array $payment): void
    {
        switch ($payment[2]) {
            case self::userLegal:
                $this->countLegalFee($payment);
                break;
            case self::userNatural:
                $this->countNaturalFee($payment);
                $this->history->setPayment($payment);
                break;
        }
    }

    /**
     * @param array $payment
     */
    private function countLegalFee(array $payment): void
    {
        [, , , , $amount, $currency] = $payment;

        $fee = $amount * (self::legalFeePercent / 100);
        if ($currency === 'EUR') {
            if ($fee < self::legalMinFee) {
                $fee_rounded = $this->moneyExchanger->roundByCurrency($currency, self::legalMinFee);
            } else {
                $fee_rounded = $this->moneyExchanger->roundByCurrency($currency, $fee);
            }

            echo $fee_rounded;
            return;
        }

        $fee_in_eur = $this->moneyExchanger->convertToEur($currency, $fee);
        if ($fee_in_eur < self::legalMinFee) {
            $fee = $this->moneyExchanger->exchangeToOriginalCurrency(self::legalMinFee, $currency);
            $fee_rounded = $this->moneyExchanger->roundByCurrency($currency, $fee);
        } else {
            $fee_rounded = $this->moneyExchanger->roundByCurrency($currency, $fee);
        }

        echo $fee_rounded;
    }

    /**
     * @param array $payment
     */
    private function countNaturalFee($payment): void
    {
        [$date, $user, , , $amount, $currency] = $payment;
        $amount = (float)$amount;

        $history_payments = $this->history->gerUserPayments($user);
        $payments_in_week = $this->paymentsByWeek($date, $history_payments);
        // jei mokejimu daugiau nei leistina skaiciuojami fee (nereikalinga valiutu keitimas
        if (count($payments_in_week) > self::naturalFreeTimes) {
            $fee = $this->countFee($amount);
            $fee_rounded = $this->moneyExchanger->roundByCurrency($currency, $fee);
            echo $fee_rounded;
            return;
        }

        $week_amounts_array = array_map(static function ($payment) {
            return [$payment[5], $payment[4]];
        }, $payments_in_week);
        $week_payments_amount = $this->moneyExchanger->totalAmountInEur($week_amounts_array);

        // jei dienos savaites limitas jau virstijas
        if ($week_payments_amount > self::naturalFreeCharge) {
            $fee = $this->countFee($amount);
            $fee_rounded = $this->moneyExchanger->roundByCurrency($currency, $fee);
            echo $fee_rounded;
            return;
        }

        $payment_eur = $this->moneyExchanger->convertToEur($currency, $amount);
        $total = $week_payments_amount + $payment_eur;

        //jei savaites limitas virsitas tik su siuo mokejimu
        if ($total > self::naturalFreeCharge) {
            $fee_amount = $total - self::naturalFreeCharge;
            $fee_in_eur = $this->countFee($fee_amount);
            $fee = $this->moneyExchanger->exchangeToOriginalCurrency($fee_in_eur, $currency);
            $fee_rounded = $this->moneyExchanger->roundByCurrency($currency, $fee);
            echo $fee_rounded;
            return;
        }

        if ($total <= self::naturalFreeCharge) {
            echo 0 . PHP_EOL;
            return;
        }
    }

    /**
     * @param string $date
     * @param array $history_payments
     * @return array
     */
    private function paymentsByWeek(string $date, array $history_payments): array
    {
        $payment_week = date("oW", strtotime($date));
        $data = [];
        foreach ($history_payments as $payment) {
            if ($payment_week === date("oW", strtotime($payment[0]))) {
                $data[] = $payment;
            }
        }

        return $data;
    }

    /**
     * @param float $amount
     * @return float
     */
    private function countFee(float $amount): float
    {
        return $amount * (self::naturalFeePercent / 100);
    }

}
