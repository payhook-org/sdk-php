<?php

use Payhook\Sdk\Payhook;
use PHPUnit\Framework\TestCase;

class NanosTest extends TestCase
{
    public function testSimpleIntMoneyToNanos(): void
    {
        $money = "228";
        $nanos = Payhook::moneyToNanos($money);

        $this->assertEquals("228000000000", $nanos);
    }

    public function testComplexIntMoneyToNanos(): void
    {
        $money = "2284384943894893";
        $nanos = Payhook::moneyToNanos($money);

        $this->assertEquals("2284384943894893000000000", $nanos);
    }

    public function testSimpleFloatMoneyToNanos(): void
    {
        $money = "1.4323";
        $nanos = Payhook::moneyToNanos($money);

        $this->assertEquals("1432300000", $nanos);
    }

    public function testSimple2FloatMoneyToNanos(): void
    {
        $money = "10,5";
        $nanos = Payhook::moneyToNanos($money);

        $this->assertEquals("10500000000", $nanos);
    }

    public function testComplexFloatMoneyToNanos(): void
    {
        $money = "94938984938985353498.4324823402304902394023";
        $nanos = Payhook::moneyToNanos($money);

        $this->assertEquals("94938984938985353498432482340", $nanos);
    }

    public function testSimpleNanosToInt(): void
    {
        $nanos = "1";
        $money = Payhook::nanosToMoney($nanos);

        $this->assertEquals("0.000000001", $money);
    }

    public function testSimple1NanosToInt(): void
    {
        $nanos = "1000000000";
        $money = Payhook::nanosToMoney($nanos);

        $this->assertEquals("1", $money);
    }

    public function testSimple2NanosToInt(): void
    {
        $nanos = "1300000000";
        $money = Payhook::nanosToMoney($nanos);

        $this->assertEquals("1.3", $money);
    }

    public function testSimple3NanosToInt(): void
    {
        $nanos = "1234567890";
        $money = Payhook::nanosToMoney($nanos);

        $this->assertEquals("1.23456789", $money);
    }

    public function testSimple4NanosToInt(): void
    {
        $nanos = "111111000000000";
        $money = Payhook::nanosToMoney($nanos);

        $this->assertEquals("111111", $money);
    }

    public function testSimple5NanosToInt(): void
    {
        $nanos = "003311";
        $money = Payhook::nanosToMoney($nanos);

        $this->assertEquals("0.000003311", $money);
    }
}