<?php
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
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
