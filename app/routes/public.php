<?php

use App\Controllers\ExampleController;

/*
|--------------------------------------------------------------------------
| Valid Routes for Site
|--------------------------------------------------------------------------
*/

$router->get('/', function() {
    return view('pages/welcome');
});

$router->get('/example', [ExampleController::class, 'index']);
