<?php

namespace Tests\Unit;

use App\Core\Router;
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
        $method = 'GET';
        $router = new Router($this->container, $method, '/test');
        $expected = [
            $method => [
                '/test' => [ExampleController::class, 'test']
            ]
        ];

        $router->register($method, '/test', [ExampleController::class, 'test']);

        $this->assertEquals($router->getRoutes(), $expected);
    }

    public function test_register_post_route()
    {
        $method = 'POST';
        $router = new Router($this->container, $method, '/test');
        $expected = [
            $method => [
                '/test' => [ExampleController::class, 'test']
            ]
        ];

        $router->register($method, '/test', [ExampleController::class, 'test']);

        $this->assertEquals($router->getRoutes(), $expected);
    }

    public function test_valid_route_output()
    {
        // Fake some data
        $_SERVER['REQUEST_URI'] = '/test/123';
        $_SERVER['SCRIPT_NAME'] = '/public/index.php';

        // Create Router
        $router = new Router($this->container, 'GET', $_SERVER['REQUEST_URI']);
        $router->get(
            '/test/123',
            function () {
                return 'test';
            }
        );

        ob_start();
        $router->run();
        $this->assertEquals('test', ob_get_contents());
        ob_end_clean();
    }

    public function test_invalid_route_404_status()
    {
        // Fake some data
        $_SERVER['REQUEST_URI'] = '/test/123';
        $_SERVER['SCRIPT_NAME'] = '/public/index.php';

        // Create Router
        $router = new Router($this->container, 'GET', $_SERVER['REQUEST_URI']);

        ob_start();
        $router->run();
        $this->assertEquals(404, http_response_code());
        ob_end_clean();
    }

}
