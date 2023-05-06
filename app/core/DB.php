<?php

namespace App\Core;

use PDO;
use Exception;
use PDOException;

class DB
{
    private $pdo;
    private $defaultPdoOptions = [
        PDO::ATTR_PERSISTENT => false,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    ];

    public function __construct(
        $database,
        $username,
        $password,
        $host = '127.0.0.1',
        $port = '3306',
        $driver = 'mysql',
        $pdoOptions = null
    ) {
        try {
            $dsn = "$driver:host=$host;port=$port;dbname=$database;charset=utf8mb4";
            $this->pdo = new PDO($dsn, $username, $password, $pdoOptions ?? $this->defaultPdoOptions);
        } catch(PDOException $e) {
            throw new Exception('Failed to connect to database: ' . $e->getMessage());
        }
    }

    /**
     * Get the established PDO connection
     */
    public function pdo(): PDO
    {
        return $this->pdo;
    }

    /**
     * Prepares the query, binds the params, executes, and runs a fetchAll()
     */
    public function query(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $this->bindParams($stmt, $params);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Prepares the query, binds the params, executes, and runs a fetch()
     */
    public function single(string $sql, array $params = []): mixed
    {
        $stmt = $this->pdo->prepare($sql);
        $this->bindParams($stmt, $params);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Prepares the query, binds the params, and executes the query
     */
    public function execute(string $sql, array $params = []): bool
    {
        $stmt = $this->pdo->prepare($sql);
        $this->bindParams($stmt, $params);
        return $stmt->execute($this->getExecuteParams($params));
    }

    private function bindParams($stmt, array $params)
    {
        if (empty($params)) {
            return;
        }
        if (is_array($params) && array_keys($params) !== range(0, count($params) - 1)) {
            foreach($params as $param_name => $param_value) {
                $stmt->bindValue(":$param_name", $param_value);
            }
        } else {
            foreach($params as $index => $param_value) {
                $stmt->bindValue($index + 1, $param_value);
            }
        }
    }

    private function getExecuteParams(array $params)
    {
        if (count($params) == 0) {
            return [];
        }
        if (is_array($params) && array_keys($params) !== range(0, count($params) - 1)) {
            return $params;
        } else {
            return array_values($params);
        }
    }
}
