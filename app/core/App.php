<?php

namespace App\Core;

use App\Core\Router;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class App
{
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function run()
    {
        // Site routing
        $di = new RecursiveDirectoryIterator('../routes/');
        foreach (new RecursiveIteratorIterator($di) as $filename) {
            if (strpos($filename, '.php') !== false) {
                require_once($filename);
            }
        }
        $this->router->checkRoute();
    }
}
