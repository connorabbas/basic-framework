<?php

namespace App\Core;

use App\Core\View;
use App\Core\Container;

class Router
{
    private $container;
    private $requestHttpMethod;
    private $requestUri;
    private $routes = [];
    private $wildCards = [];
    private $controllerBatch = null;
    private $prefixUriBatch = null;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->requestHttpMethod = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];
        $this->requestUri = parse_url($_SERVER['REQUEST_URI'])['path'];
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function run()
    {
        $uriParts = explode('/', $this->requestUri);

        foreach ($this->routes as $httpVerb => $routes) {
            foreach ($routes as $route => $callback) {
                $routeParts = explode('/', $route);
                // if identical matched uri to route
                if (
                    $route === $this->requestUri &&
                    $this->requestHttpMethod == $httpVerb
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
                            $this->requestHttpMethod == $httpVerb
                        ) {
                            foreach ($wildCards as $wildCard => $value) {
                                $this->wildCards[$wildCard] = $value;
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
        $args = (count($this->wildCards) > 0)
            ? array_values($this->wildCards)
            : [];
        if (is_array($callback)) {
            if (is_string($callback[0]) && is_string($callback[1]) && count($callback) == 2) {
                $className = $callback[0];
                $methodName = $callback[1];
                $fullClassName = "\\$className";
                $obj = $this->container->get($fullClassName);
                return call_user_func_array([$obj, $methodName],  $args);
            }
        } else if (is_callable($callback)) {
            return call_user_func($callback, $args);
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

    private function routeNotFound()
    {
        http_response_code(404);
        require __DIR__ . '/../../app/views/pages/404.php';
        return;
    }

    public function register(string $httpVerb, string $route, $callback): self
    {
        if (!is_null($this->controllerBatch)) {
            $callback = [$this->controllerBatch, $callback];
        }
        if (!is_null($this->prefixUriBatch)) {
            $route = $this->prefixUriBatch . (($route == '/') ? '' : $route);
        }
        $this->routes[$httpVerb][$route] = $callback;

        return $this;
    }

    public function get($route, $callback)
    {
        return $this->register('GET', $route, $callback);
    }

    public function post($route, $callback)
    {
        return $this->register('POST', $route, $callback);
    }

    public function patch($route, $callback)
    {
        return $this->register('PATCH', $route, $callback);
    }

    public function put($route, $callback)
    {
        return $this->register('PUT', $route, $callback);
    }

    public function delete($route, $callback)
    {
        return $this->register('DELETE', $route, $callback);
    }

    public function view($route, $view)
    {
        return $this->get($route, $view);
    }

    public function controller(string $className): self
    {
        $this->controllerBatch = $className;
        return $this;
    }

    public function prefixUri(string $uri): self
    {
        $this->prefixUriBatch = $uri;
        return $this;
    }

    public function batch(callable $closure): self
    {
        call_user_func($closure);
        $this->controllerBatch = null;
        $this->prefixUriBatch = null;

        return $this;
    }
}
