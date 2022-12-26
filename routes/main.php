<?php

use App\Controllers\ExampleController;

// Valid Routes for Site

$this->router->view('/', 'pages.welcome');

$this->router->get(
    '/json/$test',
    function () {
        return json_encode(
            [
                'foo' => 'bar',
                'test' => $_REQUEST['test'],
            ]
        );
    }
);

$this->router->get('/example', [ExampleController::class, 'index']);
