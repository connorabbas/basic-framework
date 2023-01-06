<?php

namespace App\Core;

use App\Core\View;
use App\Core\Container;

class Router
{
    private $routes = [];
    private $container;
    private const NEEDS_SPOOF = ['PUT', 'PATCH', 'DELETE'];

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    private function set(string $httpMethod, string $route, $callback)
    {
        $this->routes[$httpMethod][$route] = $callback;
    }

    public function run()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'])['path'];
        $uriParts = explode('/', $uri);

        foreach ($this->routes as $httpMethod => $routes) {
            foreach ($routes as $route => $callback) {
                $routeParts = explode('/', $route);
                // account for method spoofing
                if (in_array($httpMethod, self::NEEDS_SPOOF)) {
                    $this->handleMethodSpoof($httpMethod);
                }
                // if identical matched uri to route
                if (
                    $route === $uri &&
                    array_key_exists($uri, $routes) &&
                    $_SERVER['REQUEST_METHOD'] == $httpMethod
                ) {
                    return $this->resolveRoute($callback);
                }
                // if the route has wildcard parameters
                else if (
                    (count($routeParts) == count($uriParts)) && 
                    stripos(json_encode($routeParts), '#') !== false
                ) {
                    $matchedIndexes = [];
                    $noMatchIndexes = [];
                    $wildCardIndexes = [];
                    $nonWildCardIndexes = [];
                    $wildCards = [];
                    if (count($routeParts)) {
                        for ($i = 0; $i < count($routeParts); $i++) {
                            $routePart = $routeParts[$i];
                            if ($routePart != '') {
                                if (preg_match("/^[#]/", $routePart)) {
                                    $wildCardIndexes[] = $i;
                                    $wildCard = ltrim($routePart, '#');
                                    $$wildCard = $uriParts[$i];
                                    $wildCards[$wildCard] = $$wildCard;
                                } else {
                                    $nonWildCardIndexes[] = $i;
                                }
                                if ($routePart === $uriParts[$i]) {
                                    $matchedIndexes[] = $i;
                                } else {
                                    $noMatchIndexes[] = $i;
                                }
                            }
                        }
                        if (
                            (count($wildCardIndexes) > 0) &&
                            ($noMatchIndexes === $wildCardIndexes) &&
                            ($matchedIndexes === $nonWildCardIndexes)  &&
                            $_SERVER['REQUEST_METHOD'] == $httpMethod
                        ) {
                            foreach ($wildCards as $wildCard => $value) {
                                if (!isset($_REQUEST[$wildCard])) {
                                    $_REQUEST[$wildCard] = $value;
                                }
                            }
                            return $this->resolveRoute($callback);
                        }
                    }
                }
            }
        }

        return $this->routeNotFound();
    }

    public function handleCallback($callback)
    {
        if (is_array($callback)) {
            if (is_string($callback[0]) && is_string($callback[1]) && count($callback) == 2) {
                $className = $callback[0];
                $methodName = $callback[1];
                $fullClassName = "\\$className";
                $obj = $this->container->get($fullClassName);
                return call_user_func_array([$obj, $methodName],  []);
            }
        } else if (is_callable($callback)) {
            return call_user_func($callback);
        } else if (is_string($callback)) {
            return View::render($callback);
        }
    }

    private function resolveRoute($callback)
    {
        $returned = $this->handleCallback($callback);
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

    public function routeNotFound()
    {
        http_response_code(404);
        echo View::render('pages.404');
        return;
    }

    public function handleMethodSpoof(string $method)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_REQUEST['_method']) && $_REQUEST['_method'] === $method) {
            $_SERVER['REQUEST_METHOD'] = $method;
        }
    }

    public function get($route, $callback)
    {
        $this->set('GET', $route, $callback);
    }

    public function post($route, $callback)
    {
        $this->set('POST', $route, $callback);
    }

    public function patch($route, $callback)
    {
        $this->set('PATCH', $route, $callback);
    }

    public function put($route, $callback)
    {
        $this->set('PUT', $route, $callback);
    }

    public function delete($route, $callback)
    {
        $this->set('DELETE', $route, $callback);
    }

    public function view($route, $view)
    {
        $this->get($route, $view);
    }
}
