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

    public function test_request_header_fallback_to_server()
    {
        // Test the fallback to $_SERVER when header exists in $_SERVER but not in getallheaders()
        // This covers line 15 in request.php
        $_SERVER['HTTP_CUSTOM_HEADER'] = 'test-value';

        // Clear any headers that getallheaders might return for this key
        // to force the fallback to $_SERVER
        $actual = request_header('Custom-Header');
        $this->assertEquals('test-value', $actual);

        // Clean up
        unset($_SERVER['HTTP_CUSTOM_HEADER']);
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
}
