<?php

class ResponseTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function test_json_out()
    {
        $actual = json_out(['hello'=>'world']);
        $this->assertEquals('{"hello":"world"}', $actual);
    }
}
