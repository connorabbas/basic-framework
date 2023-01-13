<?php

namespace Tests\Unit;

use App\Core\Router;
use App\Core\Container;
use PHPUnit\Framework\TestCase;
use App\Controllers\ExampleController;

/**
 * @covers App\Core\Router
 */
class RouterTest extends TestCase
{
    private $container;

    public function setUp(): void
    {
        parent::setUp();
        $this->container = new Container();
    }

    public function testRegisterGetRoute()
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

    public function testRegisterPostRoute()
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


}
