<?php

namespace App\Controllers;

use App\Core\DB;

class ExampleController extends SiteController
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

        return view('pages/example', [
            'foo' => $foo,
        ]);
    }
}
