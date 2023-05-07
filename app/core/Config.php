<?php

namespace App\Core;

class Config
{
    private $env = [];

    public function __construct(array $envData)
    {
        $this->env = $envData;
    }

    /**
     * Return a configuration file value
     * Access values by using "." as the nesting delimiter for the $key
     */
    public function get(string $file, string $key): mixed
    {
        $configData =  require __DIR__ . '/../../config/' . $file . '.php';
        $configKeys = explode('.', $key);
        $value = $configData;
        for ($i = 0; $i < count($configKeys); $i++) {
            $value = $value[$configKeys[$i]];
        }

        return $value;
    }
}
