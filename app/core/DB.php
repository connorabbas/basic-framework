<?php

namespace App\Core;

use PDO;
use PDOException;

/* 
*  PDO DATABASE CLASS
*  Connects Database Using PDO
*  Creates Prepared Statements
*  Binds params to values
*  Returns rows and results
*/

class DB
{
	private $host;
    private $user;
    private $pass;
    private $dbname;
    private $driver;
    private $dbh;
    private $stmt;
    private $error;
	
	public function __construct(array $config = null)
    {
        // for the sake of the framework, use helper function for default configuration
        if (is_null($config)) {
            $config = config('database.main');
        }

        // Set connection vars
        $this->driver = $config['driver'];  
        $this->host = $config['host'];  
        $this->user = $config['username'];     
        $this->pass = $config['password'];
        $this->dbname = $config['name'];

		// Set DSN
		$dsn = $this->driver . ':host=' . $this->host . ';dbname=' . $this->dbname;

        // PDO Options
		$options = [
			PDO::ATTR_PERSISTENT => $config['pdo_persistent'],
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION 
        ];

		// Create a new PDO instance
		try {
			$this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
		}
		catch (PDOException $e) {
			$this->error = $e->getMessage();
		}
	}

    /**
     * Just the connection
     */
	public function conn()
    {
		return $this->dbh;
	}
	
    /**
     * Prepare statement with query
     */
	public function query($query)
    {
		$this->stmt = $this->dbh->prepare($query);
        return $this;
	}
	
	/**
     * Bind values
     */
	public function bind($param, $value, $type = null)
    {
		if (is_null($type)) {
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

        return $this;
	}
	
	/**
     * Execute the prepared statement
     */
	public function execute()
    {
		return $this->stmt->execute();
	}
	
	/**
     * Get result set as array of objects
     */
	public function resultSet()
    {
		$this->execute();
		return $this->stmt->fetchAll(PDO::FETCH_OBJ);
	}
	
	/**
     * Get single record as object
     */
	public function single()
    {
		$this->execute();
		return $this->stmt->fetch(PDO::FETCH_OBJ);
	}
	
	/**
     * Get record row count
     */
	public function rowCount()
    {
		return $this->stmt->rowCount();
	}
	
	/**
     * Returns the last inserted ID
     */
	public function lastInsertId()
    {
		return $this->dbh->lastInsertId();
	}
}
