<?php

namespace App\MVC\Models;

class Example extends Model
{
    public function exampleQuery($data)
    {
        $sql = "SELECT * FROM schema.table Where column = :data";

        $this->db->query($sql)
            ->bind(':data', $data);

        return $this->db->resultSet();
    }
}
