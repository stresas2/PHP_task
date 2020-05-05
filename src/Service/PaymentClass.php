<?php

declare(strict_types=1);

namespace Task\CommissionTask\Service;

class PaymentClass
{
    private const cashOut = 'cash_out';
    private const cashIn = 'cash_in';

    /**
     * @var array
     */
    private $payments;
    /**
     * @var CashInClass
     */
    private $cashIn;
    /**
     * @var CashOutClass
     */
    private $cashOut;

    /**
     * PaymentClass constructor.
     * @param array $payment_all
     */
    public function __construct(array $payment_all)
    {
        $this->payments = $payment_all;
        $this->cashIn = new CashInClass(new MoneyExchangeClass());
        $this->cashOut = new CashOutClass(new HistoryClass(), new MoneyExchangeClass());
    }

    public function doPayments(): void
    {
        foreach ($this->payments as $payment) {
            switch ($payment[3]) {
                case self::cashIn:
                    $this->cashIn->countFee($payment);
                    break;
                case self::cashOut:
                    $this->cashOut->countFeeByUser($payment);
                    break;
            }
        }
    }
}
