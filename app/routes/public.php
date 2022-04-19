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

$router->get('/test/$var', function() {
    echo 'test with ' . $_REQUEST['var'];
});

$router->get('/tester', [TestController::class, 'index']);
