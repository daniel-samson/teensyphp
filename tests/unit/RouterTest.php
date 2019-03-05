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
        $_SERVER['QUERY_STRING'] = '';
        $actual = url_path('');
        $this->assertTrue($actual);
        $actual = url_path('*');
        $this->assertTrue($actual);
    }

    // tests
    public function test_url_path_params()
    {
        $_SERVER['QUERY_STRING'] = 'user/1';
        $this->assertTrue(url_path_params('/user/:id'));
        $this->assertArrayHasKey(':id', $_GET);
        $this->assertEquals(1, $_GET[':id']);
        $this->assertFalse(url_path_params('/user/:id/:page'));

        $_SERVER['QUERY_STRING'] = '1';
        $this->assertTrue(url_path_params('/:id'));
        $this->assertFalse(url_path_params('/1'));
    }

    public function test_template()
    {
        $actual = template(codecept_data_dir().'/template.php', ['test' => 'hello']);
        $expected = '<h1>hello</h1>';
        $this->assertEquals($expected, $actual);
    }
}
