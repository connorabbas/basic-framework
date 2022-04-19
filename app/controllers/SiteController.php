<?php

namespace App\Controllers;

use App\Core\Router;

class SiteController
{
    public function invoke()
    {
        // Site routing
        $router = new Router();
        foreach (glob("../app/routes/*.php") as $filename) {
            require_once($filename);
        }
        $router->checkRoute();
    }
}
