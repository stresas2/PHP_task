<?php

declare(strict_types=1);

namespace Task\CommissionTask\Service;

class HistoryClass
{
    public $history = [];

    /**
     * @param array $payment
     */
    public function setPayment(array $payment): void
    {
        $this->history[] = $payment;
    }

    /**
     * @param $user
     * @return array
     */
    public function gerUserPayments($user): array
    {
        $data = [];
        foreach ($this->history as $payment) {
            if ($payment[1] === $user) {
                $data[] = $payment;
            }
        }

        return $data;
    }
}
