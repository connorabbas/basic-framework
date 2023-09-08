<?php

namespace App\Controllers;

use BasicFramework\Core\View;

class ExampleController
{
    /**
     * simple example of passing data to a view
     */
    public function index($data)
    {
        return View::render('pages.example', ['data' => $data]);
    }
}
