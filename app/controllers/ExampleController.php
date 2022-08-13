<?php

namespace App\Controllers;

use App\Core\DB;
use App\Core\View;

class ExampleController
{
    protected $db;

    public function __construct()
    {
        $this->db = new DB();
    }

    // simple example of passing data to a view
    public function index()
    {
        $foo = 'bar';

        View::show('pages/example', [
            'foo' => $foo,
        ]);
    }
}
