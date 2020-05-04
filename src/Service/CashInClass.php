<?php

declare(strict_types=1);

namespace Task\CommissionTask\Service;

class CashInClass
{
    private const CommissionFeePercent = 0.03;
    private const MaxFee = 5.00;

    /**
     * @var PrintOutClass
     */
    private $PrintOut;

    public function __construct(PrintOutClass $print_out)
    {
        $this->PrintOut = $print_out;
    }

    /**
     * @param array $payment
     */
    public function countFee(array $payment): void
    {
        $fee = $payment[4] * (self::CommissionFeePercent / 100);
        if ($fee > self::MaxFee) {
            $fee = self::MaxFee;
        }

        $this->PrintOut->print($payment[5], $fee);
    }
}
