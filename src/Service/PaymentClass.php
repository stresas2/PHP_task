<?php

declare(strict_types=1);

namespace Task\CommissionTask\Service;

class PaymentClass
{
    private const cashOut = 'cash_out';
    private const cashIn = 'cash_in';

    public const C_EUR = 'EUR';
    public const C_USD = 'USD';
    public const C_JPY = 'JPY';

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
        $this->cashIn = new CashInClass(new PrintOutClass());
        $this->cashOut = new CashOutClass(new HistoryClass(), new PrintOutClass());
    }

    public function doPayments(): void
    {
        foreach ($this->payments as $payment) {
            switch ($payment[3]) {
                case self::cashIn:
                    $this->cashIn->countFee($payment);
                    break;
                case self::cashOut:
                    $this->cashOut->countFee($payment);
                    break;
            }
        }
    }
}
