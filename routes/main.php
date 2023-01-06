<?php

use App\Controllers\ExampleController;

/**
 * Valid Routes for Site
 */

$this->router->view('/', 'pages.welcome');

$this->router->get(
    '/json/#foo',
    function () {
        return json_encode(['foo' => $_REQUEST['foo']]);
    }
);

$this->router->get('/example', [ExampleController::class, 'index']);
