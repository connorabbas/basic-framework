<?php
class Example
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function exampleQuery($data)
    {
		$sql = "SELECT * FROM schema.table Where column = :data";
        $this->db->query($sql);
        $this->db->bind(':data', $data);
        $results = $this->db->resultSet();

        return $results;
	}
}
