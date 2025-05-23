<?php
require_once __DIR__ . "/_data/SingleActionController.php";

use PHPUnit\Framework\TestCase;



class RouterTest extends TestCase
{
    public function tearDown(): void
    {
        global $PATH_PREFIX;
        $PATH_PREFIX = '';
        $_GET['url'] = '';
    }

    public function test_method()
    {
        $_SERVER['REQUEST_METHOD'] = PUT;
        $this->assertTrue(method(PUT));
        $this->assertFalse(method(POST));
    }

    public function test_url_path()
    {
        $_GET['url'] = '';
        $actual = url_path('');
        $this->assertTrue($actual);

        $actual = url_path('/');
        $this->assertTrue($actual);

        $actual = url_path('*');
        $this->assertTrue($actual);

        $_GET['url'] = 'hello';
        $actual = url_path('/hello');
        $this->assertTrue($actual);

        // test group
        global $PATH_PREFIX;
        $PATH_PREFIX = 'api';
        $_GET['url'] = 'api/hello';
        $actual = url_path('/hello');
        $this->assertTrue($actual);
    }

    public function test_url_path_params()
    {
        $_GET['url'] = 'user/1';
        $this->assertTrue(url_path_params('/user/:id'));
        $this->assertArrayHasKey(':id', $_GET);
        $this->assertEquals(1, $_GET[':id']);
        $this->assertFalse(url_path_params('/user/:id/:page'));

        $_GET['url'] = '1';
        $this->assertTrue(url_path_params('/:id'));
        $this->assertFalse(url_path_params('/1'));

        // test group
        global $PATH_PREFIX;
        $PATH_PREFIX = 'api';
        $_GET['url'] = 'api/hello/1';
        $actual = url_path_params('/hello/:id');
        $this->assertTrue($actual);
    }

    public function test_template()
    {
        $actual = template(__DIR__ . '/_data/template.php', ['test' => 'hello']);
        $expected = '<h1>hello</h1>';
        $this->assertEquals($expected, $actual);
    }

    public function test_route()
    {
        $this->assertEmpty(route(false, false, function () {
            $_GET['route_test_1'] = 1;
        }));
        $this->assertArrayNotHasKey('route_test_1', $_GET);

        $this->assertEmpty(route(true, false, function () {
            $_GET['route_test_2'] = 1;
        }));
        $this->assertArrayNotHasKey('route_test_2', $_GET);

        $this->assertEmpty(route(false, true, function () {
            $_GET['route_test_3'] = 1;
        }));
        $this->assertArrayNotHasKey('route_test_3', $_GET);


        $this->assertEmpty(route(true, true, function () {
            $_GET['route_test_4'] = 1;
        }));
        $this->assertArrayHasKey('route_test_4', $_GET);
    }

    public function test_router()
    {
        router(function () {
            stop();
        });

        $this->assertArrayHasKey('TEENSYPHP_STOP_CODE', $GLOBALS);
    }

    public function test_router_exception()
    {
        // Temporarily redirect error_log to a file or null sink
        ini_set('error_log', '/dev/null');

        ob_start();
        router(function () {
            throw new Exception("This is an exception.", 500);
        });
        $output = ob_get_clean();

        $this->assertEquals(500, http_response_code());
        // Assert the output is the correct JSON message
        $this->assertJson($output);
        $this->assertEquals(
            json_encode(["error" => "This is an exception."]),
            $output
        );
    }

    public function test_router_error()
    {
        // Temporarily redirect error_log to a file or null sink
        ini_set('error_log', '/dev/null');

        ob_start();
        router(function () {
            throw new Error("This is an error.", 400);
        });
        $output = ob_get_clean();
        $this->assertEquals(400, http_response_code());
        // Assert the output is the correct JSON message
        $this->assertJson($output);
        $this->assertEquals(
            json_encode(["error" => "This is an error."]),
            $output
        );
    }

    public function test_router_group()
    {
        global $PATH_PREFIX;
        $PATH_PREFIX = '';
        $_GET['url'] = 'api/hello';
        ob_start();
        router(function () {
            routerGroup('/api', function () {
                route(true, url_path('/hello'), function () {
                    echo 'hello';
                });
            });
        });
        $output = ob_get_clean();
        $this->assertEquals('hello', $output);

        $PATH_PREFIX = '';
        $_GET['url'] = 'api/hello';
        ob_start();
        router(function () {
            routerGroup('/api', function () {
                routerGroup('/hello', function () {
                    route(true, url_path('/'), function () {
                        echo 'hello2';
                    });
                });
            });
        });
        $output2 = ob_get_clean();
        $this->assertEquals('hello2', $output2);

        $PATH_PREFIX = '';
        $_GET['url'] = 'api/hello';
        ob_start();
        router(function () {
            routerGroup('/api/', function () {
                routerGroup('/hello', function () {
                    route(true, url_path('/'), function () {
                        echo 'hello2';
                    });
                });
            });
        });
        $output2 = ob_get_clean();
        $this->assertEquals('hello2', $output2);
    }
    public function test_redirect()
    {
        redirect('/');
        $headers = xdebug_get_headers();

        $this->assertEquals(1, in_array('Location: /', $headers));
    }

    public function test_use_request_uri()
    {
        $_SERVER['REQUEST_URI'] = '/';

        use_request_uri();

        $this->assertEquals('', $_GET['url']);
    }

    public function test_route_class()
    {
        ob_start();
        route(true, true, SingleActionController::class);
        $output = ob_get_clean();
        $this->assertEquals(json_encode(["message" => "Hello World"]), $output);
    }
}
