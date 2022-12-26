<?php

namespace App\Controllers;

use App\Core\View;

class ExampleController
{
    // simple example of passing data to a view
    public function index()
    {
        $foo = 'bar';

        return View::render(
            'pages.example',
            ['foo' => $foo]
        );
    }
}
