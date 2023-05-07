<?php

return [
    'main' => [
        'driver' => $this->env['DB_DRIVER'] ?? 'mysql',
        'host' => $this->env['DB_HOST'] ?? '127.0.0.1',
        'username' => $this->env['DB_USERNAME'] ?? 'root',
        'password' => $this->env['DB_PASSWORD'] ?? '',
        'name' => $this->env['DB_NAME'] ?? '',
        'pdo_options' => [
            PDO::ATTR_PERSISTENT => false,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        ]
    ],
];
