<?php

namespace Tests\Unit;

use App\Core\Router;
use App\Core\Container;
use PHPUnit\Framework\TestCase;
use App\Controllers\ExampleController;

class RouterTest extends TestCase
{
    /* private $container;

    public function setUp(): void
    {
        $this->container = new Container();
    } */

    public function testRegisterRoute()
    {
        $container = new \App\Core\Container();
        $router = new Router($container);
        $expected = [
            'GET' => [
                '/test' => [ExampleController::class, 'test']
            ]
        ];

        $router->register('GET', '/test', [ExampleController::class, 'test']);

        $this->assertEquals($router->getRoutes(), $expected);
    }

    /* public function testRegisterGetRoute()
    {
        $router = new Router($this->container);
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
        $router = new Router($this->container);
        $expected = [
            'POST' => [
                '/test' => [ExampleController::class, 'test']
            ]
        ];

        $router->register('POST', '/test', [ExampleController::class, 'test']);

        $this->assertEquals($router->getRoutes(), $expected);
    } */
}
