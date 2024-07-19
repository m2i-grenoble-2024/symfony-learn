<?php

namespace App\Tests;
use PHPUnit\Framework\TestCase;


class CalculatorTest extends TestCase {

    public function testShouldAddTwoNumbers() {
        $instance = new Calculator();
        $result = $instance->add(2,2);
        $this->assertEquals(4, $result);
    }
}