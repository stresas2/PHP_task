<?php

declare(strict_types=1);

namespace Task\CommissionTask\Service;

class CashOutClass
{
    private const userLegal = 'legal';
    private const userNatural = 'natural';
    private const legalFeePrecent = 0.3;
    private const naturalFeePrecent = 0.3;
    private const naturalFreeCharge = 1000.00;
    private const naturalFreeTimes = 3;
    private const legalMinFee = 0.50;
    private const EUR_USD = 1.1497;
    private const EUR_JPY = 129.53;

    /**
     * @var HistoryClass
     */
    private $history;
    /**
     * @var PrintOutClass
     */
    private $PrintOut;

    public function __construct(HistoryClass $history_all, PrintOutClass $print_out)
    {
        $this->history = $history_all;
        $this->PrintOut = $print_out;
    }

    /**
     * @param array $payment
     */
    public function countFee(array $payment): void
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
        $fee = $payment[4] * (self::legalFeePrecent / 100);
        if ($fee < self::legalMinFee) {
            $fee = self::legalMinFee;
        }

        $this->PrintOut->print($payment[5], $fee);
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
        if (count($payments_in_week) > self::naturalFreeTimes) {
            $this->count($currency, $amount);
            return;
        }

        $week_amounts_array = array_map(static function ($payment) {
            return [$payment[5], $payment[4]];
        }, $payments_in_week);
        $week_payments_amount = $this->totalAmountInEur($week_amounts_array);

        if ($week_payments_amount > self::naturalFreeCharge) {
            $this->count($currency, $amount);
            return;
        }

        $payment_eur = $this->totalAmountInEur([[$currency, $amount]]);
        $total = $week_payments_amount + $payment_eur;
        if ($total > self::naturalFreeCharge) {
            $fee_amount = $total - self::naturalFreeCharge;
            $this->count($currency, $fee_amount);
            return;
        }

        $this->PrintOut->print(null, 0);
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
     * @param string $currency
     * @param float $amount
     */
    private function count(string $currency, float $amount): void
    {
        $fee = $amount * (self::naturalFeePrecent / 100);
        switch ($currency) {
            case PaymentClass::C_USD:
                $fee *= self::EUR_USD;
                break;
            case PaymentClass::C_JPY:
                $fee *= self::EUR_JPY;
                break;
            default:
                break;
        }

        $this->PrintOut->print($currency, $fee);
    }

    /**
     * @param array $week_amounts
     * @return float
     */
    private function totalAmountInEur(array $week_amounts): float
    {
        $total_amount = 0;
        foreach ($week_amounts as $amount) {
            switch ($amount[0]) {
                case PaymentClass::C_USD:
                    $total_amount += $amount[1] / self::EUR_USD;
                    break;
                case PaymentClass::C_JPY:
                    $total_amount += $amount[1] / self::EUR_JPY;
                    break;
                case PaymentClass::C_EUR:
                    $total_amount += $amount[1];
                    break;
            }
        }

        return $total_amount;
    }
}
