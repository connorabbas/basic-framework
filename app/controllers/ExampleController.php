<?php

namespace App\Controllers;

use App\Core\View;
use App\Data\Config;

class ExampleController
{
    private $db;

    public function __construct(Config $test)
    {
        dd($test);
    }

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
