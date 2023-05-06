<?php

use App\Controllers\ExampleController;

/**
 * Registered routes for your site
 */

$this->router->view('/', 'pages.welcome');

$this->router->get('/json', function () {
    return json_encode(['foo' => 'bar']);
});

$this->router->get('/example/#data', [ExampleController::class, 'index']);
