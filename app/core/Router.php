<?php

namespace App\Core;

use App\Core\View;

class Router
{
    public $validRoute;

    public function __construct()
    {
        $this->validRoute = false;
    }

    public function set($route, $callback)
    {
        $dirParts = explode('\\', __DIR__);
        $projectName = $dirParts[count($dirParts) - 3];
        $publicPath = '/' . $projectName . '/public/';
        $requestUrl = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
        $requestUrl = str_replace(ltrim($publicPath, '/'), '',  $requestUrl);
        $requestUrl = rtrim($requestUrl, '/');
        $requestUrl = strtok($requestUrl, '?');
        $routeParts = explode('/', $route);
        $requestUrlParts = explode('/', $requestUrl);
        array_shift($routeParts);
        array_shift($requestUrlParts);
    
        if ($routeParts[0] == '' && count($requestUrlParts) == 0 ) {
            $this->callRoute($callback);
            exit();
        }
        if (count($routeParts) != count($requestUrlParts)) {
            return;
        } 
    
        $parameters = [];
        for ($i = 0; $i < count($routeParts); $i++) {
            $routePart = $routeParts[$i];
            if (preg_match("/^[$]/", $routePart)) {
                $routePart = ltrim($routePart, '$');
                array_push($parameters, $requestUrlParts[$i]);
                $$routePart = $requestUrlParts[$i];
                // Set dynamic route variables
                $_REQUEST[$routePart] = $$routePart;
            }
            else if ($routeParts[$i] != $requestUrlParts[$i]) {
                return;
            } 
        }
        $this->callRoute($callback);
        exit();
    }

    public function callRoute($callback)
    {
        if (is_array($callback)) {
            if (is_string($callback[0]) && is_string($callback[1]) && count($callback) == 2) {
                // Instantiate class and call method
                $className = $callback[0];
                $methodName = $callback[1];
                $fullClassName = "\\$className";
                $obj = new $fullClassName();
                $this->validRoute = true;
                return call_user_func([$obj, $methodName],  $callback[1]);
            } 
        }
        else if (is_callable($callback)) {
            $this->validRoute = true;
            return call_user_func($callback);
        }
        else if (is_string($callback)) {
            $this->validRoute = true;
            return View::show($callback);
        }
    }

    public function checkRoute()
    {
        if (!$this->validRoute) {
            return View::show('pages/404');
        }
    }

    public function view($route, $view)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->set($route, $view);
        } 
    }

    public function get($route, $callback)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->set($route, $callback);
        } 
    }

    public function post($route, $callback)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->set($route, $callback);
        } 
    }

    public function patch($route, $callback)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
            $this->set($route, $callback);
        } 
    }

    public function put($route, $callback)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $this->set($route, $callback);
        } 
    }

    public function delete($route, $callback)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $this->set($route, $callback);
        } 
    }
}
