<?php

namespace App\Controllers;

use App\Core\Router;

class SiteController
{
    public function invoke()
    {
        // Site routing
        $router = new Router();
        $di = new \RecursiveDirectoryIterator('../routes/');
        foreach (new \RecursiveIteratorIterator($di) as $filename => $file) {
            if (strpos($filename, '.php') !== false) {
                require_once($filename);
            }
        }
        $router->checkRoute();
    }
}
