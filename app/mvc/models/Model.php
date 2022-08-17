<?php

namespace App\MVC\Models;

use App\Core\DB;

class Model
{
    protected $db;

    public function __construct(DB $db)
    {
        $this->db = $db;
    }
}
