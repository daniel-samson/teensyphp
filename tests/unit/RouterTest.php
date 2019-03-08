<?php

class RouterTest extends \Codeception\Test\Unit
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
    }

    // tests
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
    }

    public function test_template()
    {
        $actual = template(codecept_data_dir().'/template.php', ['test' => 'hello']);
        $expected = '<h1>hello</h1>';
        $this->assertEquals($expected, $actual);
    }

    public function test_route()
    {
        $this->assertEmpty(route(false, false, function() {$_GET['route_test_1'] = 1;}));
        $this->assertArrayNotHasKey('route_test_1', $_GET);

        $this->assertEmpty(route(true, false, function() {$_GET['route_test_2'] = 1;}));
        $this->assertArrayNotHasKey('route_test_2', $_GET);

        $this->assertEmpty(route(false, true, function() {$_GET['route_test_3'] = 1;}));
        $this->assertArrayNotHasKey('route_test_3', $_GET);
        // can't test running the method due to the exit call in route
    }
}
