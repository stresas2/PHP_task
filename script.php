<?php

require_once('vendor/autoload.php');

use Task\CommissionTask\Service\PaymentClass;

if (!is_array($argv) || count($argv) !== 2 || !is_string($argv[1])) {
    echo 'sorry mistake' . PHP_EOL;
    return;
}

$file_name = $argv[1];

try {
    $csv = array_map('str_getcsv', file($file_name));
} catch (Throwable $e) {
    echo 'Captured Throwable: ' . $e->getMessage() . PHP_EOL;
}

$payments = new PaymentClass($csv);

$payments->doPayments();
