<?php

class MiddlewareTest extends \Codeception\Test\Unit
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

    public function test_middleware()
    {
        $actual = middleware(
            function() {
                usleep(100);
                $_GET['test_middleware_1'] = microtime(true);
            },
            function() {
                usleep(100);
                $_GET['test_middleware_2'] = microtime(true);
            }
        );

        call_user_func($actual, []);

        $this->assertArrayHasKey('test_middleware_1', $_GET);
        $this->assertArrayHasKey('test_middleware_2', $_GET);
        $this->assertGreaterThan($_GET['test_middleware_1'], $_GET['test_middleware_2']);
    }
}
