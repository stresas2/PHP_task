<?php

require_once('vendor/autoload.php');

use Task\CommissionTask\Service\PaymentClass;

if (!is_array($argv) || count($argv) !== 2 || !is_string($argv[1])) {
    throw new \RuntimeException('Wrong input!');
}

$file_name = $argv[1];

try {
    $csv = array_map('str_getcsv', file($file_name));
} catch (Throwable $e) {
    throw new \RuntimeException('Bad file input!');
}

$payments = new PaymentClass($csv);

$payments->doPayments();
