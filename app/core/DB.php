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
    private $dbName;
    private $driver;
    private $conn;
    private $stmt;

    public function __construct(array $config = null)
    {
        // for the sake of the framework, use the config helper function for the default configuration
        if (is_null($config)) {
            $config = config('database.main');
        }
        
        // PDO Options
        if (!isset($config['pdo_options'])) {
            $config['pdo_options'] = [
                PDO::ATTR_PERSISTENT => false,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            ];
        }

        // Set connection vars
        $this->driver = $config['driver'];
        $this->host = $config['host'];
        $this->user = $config['username'];
        $this->pass = $config['password'];
        $this->dbName = $config['name'];

        // Set DSN
        $dsn = $this->driver . ':host=' . $this->host . ';dbname=' . $this->dbName;

        // Create a new PDO instance
        try {
            $this->conn = new PDO($dsn, $this->user, $this->pass, $config['pdo_options']);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int) $e->getCode());
        }
    }

    /**
     * Just the connection
     */
    public function getConnection()
    {
        return $this->conn;
    }

    /**
     * Prepare statement with query
     */
    public function query(string $sql)
    {
        $this->stmt = $this->conn->prepare($sql);
        return $this;
    }

    /**
     * Bind values
     */
    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
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
     * Get result set as array
     */
    public function resultSet($fetchMode = null)
    {
        $this->execute();
        return $this->stmt->fetchAll($fetchMode ?? $this->conn->getAttribute(PDO::ATTR_DEFAULT_FETCH_MODE));
    }

    /**
     * Get single record
     */
    public function single($fetchMode = null)
    {
        $this->execute();
        return $this->stmt->fetch($fetchMode ?? $this->conn->getAttribute(PDO::ATTR_DEFAULT_FETCH_MODE));
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
        return $this->conn->lastInsertId();
    }
}
