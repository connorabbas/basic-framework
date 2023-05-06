<?php

namespace App\Models;

use App\Core\Model;

class ExampleModel extends Model
{
    private $table = 'example';

    public function getAll()
    {
        $sql = "SELECT * FROM $this->table";
        return $this->db->query($sql);
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM $this->table WHERE id = ?";
        return $this->db->single($sql, [$id]);
    }

    public function update(int $userId, array $properties)
    {
        $setString = '';
        foreach ($properties as $property => $value) {
            $setString .= $property . ' = ' . ':' . $property;
            if ($property != array_key_last($properties)) {
                $setString .= ', ';
            } else {
                $setString .= ' ';
            }
        }
        $properties['id'] = $userId;
        $sql = "UPDATE $this->table
            SET $setString
            WHERE id = :id";

        return $this->db->execute($sql, $properties);
    }

    public function delete(int $userId)
    {
        $sql = "DELETE FROM $this->table WHERE id = ?";
        return $this->db->execute($sql, [$userId]);
    }
}
