<?php

namespace Task\CommissionTask\Tests\Service;

use PHPUnit\Framework\TestCase;
use Task\CommissionTask\Service\PrintOutClass;

class PrintOutClassTest extends TestCase
{
    public function testPrint(): void
    {
        $print_out = new PrintOutClass();
        $expected = '5.00' . PHP_EOL;
        $this->expectOutputString($expected);
        $print_out->print('EUR', 5);
    }
}
