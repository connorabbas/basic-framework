<?php

namespace App\Core;

use App\Core\View;
use App\Core\Container;

class Router
{
    private $validRoute = false;
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    private function set(string $route, $callback)
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

        if ($routeParts[0] == '' && count($requestUrlParts) == 0) {
            $this->completeRoute($callback);
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
                if (!isset($_REQUEST[$routePart])) {
                    $_REQUEST[$routePart] = $$routePart;
                }
            } else if ($routeParts[$i] != $requestUrlParts[$i]) {
                return;
            }
        }

        $this->completeRoute($callback);
    }

    private function callRoute($callback)
    {
        if (is_array($callback)) {
            if (is_string($callback[0]) && is_string($callback[1]) && count($callback) == 2) {
                // Instantiate class and call method
                $className = $callback[0];
                $methodName = $callback[1];
                $fullClassName = "\\$className";
                // use the container to get the class
                $obj = $this->container->get($fullClassName);
                $this->validRoute = true;
                return call_user_func_array([$obj, $methodName],  []);
            }
        } else if (is_callable($callback)) {
            $this->validRoute = true;
            return call_user_func($callback);
        } else if (is_string($callback)) {
            $this->validRoute = true;
            return View::render($callback);
        }
    }

    private function completeRoute($callback)
    {
        $returned = $this->callRoute($callback);
        if (is_string($returned)) {
            $testJson = json_decode($returned);
            if (json_last_error() === JSON_ERROR_NONE) {
                header('Content-type: application/json');
            }
            echo $returned;
            return;
        }

        return $returned;
    }

    public function checkRoute()
    {
        if (!$this->validRoute) {
            http_response_code(404);
            echo View::render('pages.404');
            return;
        }
    }

    public function handleMethodSpoof(string $method)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_REQUEST['_method']) && $_REQUEST['_method'] === $method) {
            $_SERVER['REQUEST_METHOD'] = $method;
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
        $this->handleMethodSpoof('PATCH');
        if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
            $this->set($route, $callback);
        }
    }

    public function put($route, $callback)
    {
        $this->handleMethodSpoof('PUT');
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $this->set($route, $callback);
        }
    }

    public function delete($route, $callback)
    {
        $this->handleMethodSpoof('DELETE');
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $this->set($route, $callback);
        }
    }
}
