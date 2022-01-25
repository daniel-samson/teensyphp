<?php
use PHPUnit\Framework\TestCase;

if (!function_exists('getallheaders')) {
    function getallheaders() {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

class RequestTest extends TestCase
{
    public function test_request_header()
    {
        $expected = 1;
        $_SERVER['HTTP_X_TEXT'] = $expected;
        $actual = request_header('X-Text');
        $this->assertEquals($expected, $actual);


        $actual = request_header('X-Text-Empty');
        $this->assertEquals('', $actual);
    }

    public function test_accept()
    {
        $expected = 'plain/text';
        $_SERVER['HTTP_ACCEPT'] = $expected;
        $actual = accept($expected);
        $this->assertTrue($actual);

        $actual = accept('applicartion/json');
        $this->assertFalse($actual);
    }

    public function test_request_body()
    {
//        request_body();
       $this->markTestSkipped('php://input is really hard to test');
    }

    public function test_json_in()
    {
//        json_in();
        $this->markTestSkipped('php://input is really hard to test');
    }
}
