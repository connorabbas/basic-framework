<?php
/* 
*  PDO DATABASE CLASS
*  Connects Database Using PDO
*  Creates Prepeared Statements
*  Binds params to values
*  Returns rows and results
*/
class DB
{
	private $host, $user, $pass, $dbname, $dbh, $error, $stmt;
	
	public function __construct($persistent = false)
    {
        // Set connection vars
        $this->host = getenv('DB_HOST');  
        $this->user = getenv('DB_USERNAME');     
        $this->pass = getenv('DB_PASSWORD');
        $this->dbname = getenv('DB_NAME');

		// Set DSN
		$dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
		$options = array (
			PDO::ATTR_PERSISTENT => $persistent,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION 
		);

		// Create a new PDO instanace
		try {
			$this->dbh = new PDO ($dsn, $this->user, $this->pass, $options);
		}		// Catch any errors
		catch ( PDOException $e ) {
			$this->error = $e->getMessage();
		}
	}

    // Just the connection
	public function conn()
    {
		return $this->dbh;
	}
	
	// Prepare statement with query
	public function query($query)
    {
		$this->stmt = $this->dbh->prepare($query);
	}
	
	// Bind values
	public function bind($param, $value, $type = null)
    {
		if (is_null ($type)) {
			switch (true) {
				case is_int ($value) :
					$type = PDO::PARAM_INT;
					break;
				case is_bool ($value) :
					$type = PDO::PARAM_BOOL;
					break;
				case is_null ($value) :
					$type = PDO::PARAM_NULL;
					break;
				default :
					$type = PDO::PARAM_STR;
			}
		}
		$this->stmt->bindValue($param, $value, $type);
	}
	
	// Execute the prepared statement
	public function execute()
    {
		return $this->stmt->execute();
	}
	
	// Get result set as array of objects
	public function resultset()
    {
		$this->execute();
		return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	// Get single record as object
	public function single()
    {
		$this->execute();
		return $this->stmt->fetch(PDO::FETCH_ASSOC);
	}
	
	// Get record row count
	public function rowCount()
    {
		return $this->stmt->rowCount();
	}
	
	// Returns the last inserted ID
	public function lastInsertId()
    {
		return $this->dbh->lastInsertId();
	}
}