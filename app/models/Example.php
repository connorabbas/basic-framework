<?php

namespace App\Models;

use App\Core\Model;

class Example extends Model
{
    public function getData($data)
    {
        $sql = "SELECT * FROM schema.table Where column = :data";

        $this->db->query($sql)->bind(':data', $data);

        return $this->db->resultSet();
    }
}
