<?php

namespace Tests\Unit;

use App\Core\Router;
use App\Core\Request;
use App\Core\Container;
use PHPUnit\Framework\TestCase;
use App\Controllers\ExampleController;

/**
 * @covers App\Core\Router
 * https://github.com/bramus/router/blob/master/tests/RouterTest.php
 */
class RouterTest extends TestCase
{
    private $container;

    public function setUp(): void
    {
        parent::setUp();
        $this->container = new Container();
    }

    public function test_register_get_route()
    {
        $_SERVER['REQUEST_URI'] = '/test';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $router = new Router($this->container);
        $expected = [
            'GET' => [
                '/test' => [ExampleController::class, 'test']
            ]
        ];

        $router->register('GET', '/test', [ExampleController::class, 'test']);

        $this->assertEquals($router->getRoutes(), $expected);
    }

    public function test_register_post_route()
    {
        $_SERVER['REQUEST_URI'] = '/test';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $router = new Router($this->container);
        $expected = [
            'POST' => [
                '/test' => [ExampleController::class, 'test']
            ]
        ];

        $router->register('POST', '/test', [ExampleController::class, 'test']);

        $this->assertEquals($router->getRoutes(), $expected);
    }

    public function test_valid_route_output()
    {
        $_SERVER['REQUEST_URI'] = '/test/123';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $output = 'test route output';
        $router = new Router($this->container);
        $router->get('/test/123', fn() => $output);

        ob_start();
        $router->run();
        $this->assertEquals($output, ob_get_contents());
        ob_end_clean();
    }

    public function test_wildcard_route_parameters_output()
    {
        $this->markTestSkipped();
        $_SERVER['REQUEST_URI'] = '/test/123';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $router = new Router($this->container);
        $router->get('/test/#param', function ($param) {
            return 'test param: ' . $param;
        });

        ob_start();
        $router->run();
        $this->assertEquals('test param: 123', ob_get_contents());
        ob_end_clean();
    }

    public function test_invalid_route_404_status()
    {
        $_SERVER['REQUEST_URI'] = '/test/123';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $router = new Router($this->container);

        ob_start();
        $router->run();
        $this->assertEquals(404, http_response_code());
        ob_end_clean();
    }

}
