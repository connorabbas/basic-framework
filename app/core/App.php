<?php

namespace App\Core;

use App\Core\DB;
use App\Core\Config;
use App\Core\Router;
use App\Core\Request;
use App\Core\Container;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class App
{
    protected $router;
    protected $container;

    public function __construct(Container $container, Router $router)
    {
        $this->container = $container;
        $this->router = $router;
    }

    /**
     * Start the site
     */
    public function run(): void
    {
        $iterator = new RecursiveDirectoryIterator(__DIR__ . '/../../routes');
        foreach (new RecursiveIteratorIterator($iterator) as $filename) {
            if (strpos($filename, '.php') !== false) {
                require_once $filename;
            }
        }
        $this->router->run();
    }

    /**
     * Establish any container class bindings for the application
     */
    public function containerSetup(): self
    {
        $this->container->setOnce(Config::class, function ($container) {
            return new Config($_ENV);
        });
        $this->container->setOnce(Request::class, function ($container) {
            return new Request();
        });
        /* $this->container->setOnce(DB::class, function ($container) {
            $dbConfig = config('database', 'main');
            return new DB(
                $dbConfig['name'],
                $dbConfig['username'],
                $dbConfig['password'],
                $dbConfig['host'],
            );
        }); */

        return $this;
    }
}
