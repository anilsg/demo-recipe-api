<?php

use PHPUnit\Framework\TestCase;

class DummyTest extends TestCase
{
    public function testAssertEquals()
    {
        $this->assertEquals('abc', 'abc');
    }
}

