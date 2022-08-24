<?php

namespace App\Core;

use App\Core\DB;

abstract class Model
{
    protected $db;

    public function __construct(DB $db)
    {
        $this->db = $db;
    }
}
