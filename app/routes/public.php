<?php

use App\Core\App;
use App\Controllers\TestController;

/*
|--------------------------------------------------------------------------
| Valid Routes for Site
|--------------------------------------------------------------------------
*/

$router->get('/', function() {
    return App::view('pages/welcome');
});

$router->get('/tester', [TestController::class, 'index']);
