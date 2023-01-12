<?php

namespace Tests\Unit;

use App\Core\Router;
use App\Core\Container;
use PHPUnit\Framework\TestCase;
use App\Controllers\ExampleController;

class RouterTest extends TestCase
{
    private $container;

    public function setUp(): void
    {
        parent::setUp();
        $this->container = new Container();
    }

    public function testRegisterRoute()
    {
        $router = new Router($this->container, 'GET', '/test');
        $expected = [
            'GET' => [
                '/test' => [ExampleController::class, 'test']
            ]
        ];

        $router->register('GET', '/test', [ExampleController::class, 'test']);

        $this->assertEquals($router->getRoutes(), $expected);
    }

    public function testRegisterGetRoute()
    {
        $router = new Router($this->container, 'GET', '/test');
        $expected = [
            'GET' => [
                '/test' => [ExampleController::class, 'test']
            ]
        ];

        $router->register('GET', '/test', [ExampleController::class, 'test']);

        $this->assertEquals($router->getRoutes(), $expected);
    }

    public function testRegisterPostRoute()
    {
        $router = new Router($this->container, 'GET', '/test');
        $expected = [
            'POST' => [
                '/test' => [ExampleController::class, 'test']
            ]
        ];

        $router->register('POST', '/test', [ExampleController::class, 'test']);

        $this->assertEquals($router->getRoutes(), $expected);
    }
}
