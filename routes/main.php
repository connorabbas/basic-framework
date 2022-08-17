<?php

use App\MVC\Controllers\ExampleController;

// Valid Routes for Site

$router->view('/', 'pages.welcome');

$router->get('/json/$test', function () {
    echo json_encode(
        [
            'foo' => 'bar',
            'test' => $_REQUEST['test'],
        ]
    );
});

$router->get('/example', [ExampleController::class, 'index']);
