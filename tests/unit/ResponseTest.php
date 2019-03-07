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

    public function test_html_out()
    {
        $actual = html_out('<h1>a</h1>');
        $this->assertEquals('<h1>a</h1>', $actual);
    }
}
