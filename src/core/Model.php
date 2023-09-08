<?php

namespace BasicFramework\Core;

abstract class Model
{
    protected $db;

    public function __construct(DB $db)
    {
        $this->db = $db;
    }
}
