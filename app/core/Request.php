<?php

namespace App\Core;

class Request
{
    private array $get;
    private array $post;
    private array $request;

    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->request = $_REQUEST;
    }

    /**
     * An array of all sanitized inputs from the request
     */
    public function all(): array
    {
        $sanitized = [];
        foreach ($this->request as $input => $value) {
            $sanitized[$input] = $this->sanitize($value);
        }

        return $sanitized;
    }

    /**
     * The sanitized $_GET value by it's key, optional default value
     */
    public function get(string $key, string $default = null): mixed
    {
        if (isset($this->get[$key])) {
            return $this->sanitize($this->get[$key]);
        }

        return $default;
    }

    /**
     * The sanitized $_POST value by it's key, optional default value
     */
    public function post(string $key, string $default = null): mixed
    {
        if (isset($this->post[$key])) {
            return $this->sanitize($this->post[$key]);
        }

        return $default;
    }

    /**
     * The sanitized $_REQUEST value by it's key, optional default value
     */
    public function input(string $key, string $default = null): mixed
    {
        if (isset($this->request[$key])) {
            return $this->sanitize($this->request[$key]);
        }

        return $default;
    }

    public function sanitize($value)
    {
        if (is_array($value)) {
            return array_map([$this, 'sanitize'], $value);
        }
        $value = trim($value);
        $value = stripslashes($value);
        $value = htmlspecialchars($value);

        return $value;
    }
}
