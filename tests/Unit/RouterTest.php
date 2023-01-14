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

    public function test_route_valid_output()
    {
        // Fake some data
        $_SERVER['SCRIPT_NAME'] = '/public/index.php';

        // Create Router
        $router = new Router($this->container, 'GET', '/test/123');
        $router->get(
            '/test/123',
            function () {
                return 'test';
            }
        );

        // Test the /test/123 route
        ob_start();
        $_SERVER['REQUEST_URI'] = '/test/123';
        $router->run();
        $this->assertEquals('test', ob_get_contents());
        ob_end_clean();
    }

}
