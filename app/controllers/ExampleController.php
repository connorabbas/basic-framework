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

    public function index()
    {
        return view('pages/example');
    }
}