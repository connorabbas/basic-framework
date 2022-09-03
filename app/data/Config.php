<?php

namespace App\Data;

class Config
{
    private $config = [];

    public function __construct(array $env)
    {
        $this->config = [
            'site' => [
                'environment' => $env['ENV'],
                'title' => 'Welcome!',
                'description' => 'A full-stack PHP framework that gives you the basics for starting a web project in a lightweight package.
                ',
            ],
            'database' => [
                'main' => [
                    'driver' => $env['DB_DRIVER'],
                    'host' => $env['DB_HOST'],
                    'username' => $env['DB_USERNAME'],
                    'password' => $env['DB_PASSWORD'],
                    'name' => $env['DB_NAME'],
                ],
            ],
            'plates_templates' => [
                'folders' => [
                    'template' => 'templates/',
                ],
            ],
        ];
    }

    public function get()
    {
        return $this->config;
    }
}
