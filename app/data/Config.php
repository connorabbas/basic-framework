<?php

namespace App\Data;

use PDO;

class Config
{
    private $data = [];

    public function __construct(array $env)
    {
        $this->data = [
            'site' => [
                'environment' => $env['ENV'] ?? 'local',
                'title' => 'Welcome!',
                'description' => 'A full-stack PHP framework that gives you the basics for starting a web project in a lightweight package.
                ',
            ],
            'database' => [
                'main' => [
                    'driver' => $env['DB_DRIVER'] ?? 'mysql',
                    'host' => $env['DB_HOST'] ?? '127.0.0.1',
                    'username' => $env['DB_USERNAME'] ?? 'root',
                    'password' => $env['DB_PASSWORD'] ?? '',
                    'name' => $env['DB_NAME'] ?? '',
                    'pdo_options' => [
                        PDO::ATTR_PERSISTENT => false,
                        PDO::ATTR_EMULATE_PREPARES => false,
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                    ]
                ],
            ],
            'plates_templates' => [
                'folders' => [
                    'template' => '/templates',
                ],
            ],
        ];
    }

    public function get()
    {
        return $this->data;
    }
}
