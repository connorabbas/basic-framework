<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\DB;

class TestController extends SiteController
{
    protected $db;

    public function __construct()
    {
        $this->db = new DB();
    }

    public function index()
    {
        return App::view('pages/tester');
    }
}