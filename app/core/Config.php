<?php

namespace App\Core;

class Config
{
    protected $config = [];

    public function __construct(array $env)
    {
        $this->config = [
            'site' => [
                'environment' => $env['ENV'],
                'url' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",
                'title' => 'Welcome!',
                'description' => 'This is a mini framework with the basic php application essentials.',
            ],
            'database' => [
                'main' => [
                    'driver' => $env['DB_DRIVER'],
                    'host' => $env['DB_HOST'],
                    'username' => $env['DB_USERNAME'],
                    'password' => $env['DB_PASSWORD'],
                    'name' => $env['DB_NAME'],
                    'pdo_persistent' => $env['DB_PDO_PERSISTENT'],
                ],
            ],
        ];
    }

    public function get()
    {
        return $this->config;
    }
}
