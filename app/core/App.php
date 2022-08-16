<?php

namespace App\Core;

use App\Core\Router;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class App
{
    public function run()
    {
        // Site routing
        $router = new Router();
        $di = new RecursiveDirectoryIterator('../routes/');
        foreach (new RecursiveIteratorIterator($di) as $filename) {
            if (strpos($filename, '.php') !== false) {
                require_once($filename);
            }
        }
        $router->checkRoute();
    }
}
