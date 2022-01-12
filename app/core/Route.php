<?php
class Route
{
    public static function set($route, $callback)
    {
        global $ValidRoute;
    
        $request_url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
        $request_url = str_replace(ltrim(BASE_DIR, '/'), '',  $request_url);
        $request_url = rtrim($request_url, '/');
        $request_url = strtok($request_url, '?');
        $route_parts = explode('/', $route);
        $request_url_parts = explode('/', $request_url);
        array_shift($route_parts);
        array_shift($request_url_parts);
    
        // If route is only /
        if ( $route_parts[0] == '' && count($request_url_parts) == 0 ) {
            self::callRoute($route, $callback);
            exit();
        }

        if ( count($route_parts) != count($request_url_parts) ) { return; } 
    
        // If multi component route
        $parameters = [];
        for( $__i__ = 0; $__i__ < count($route_parts); $__i__++ ) {
            $route_part = $route_parts[$__i__];
            if ( preg_match("/^[$]/", $route_part) ) {
                $route_part = ltrim($route_part, '$');
                array_push($parameters, $request_url_parts[$__i__]);
                $$route_part=$request_url_parts[$__i__];
                // Set dynamic route variables for get or post
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $_POST[$route_part] = $$route_part;
                } else {
                    $_GET[$route_part] = $$route_part;
                }
            }
            else if ( $route_parts[$__i__] != $request_url_parts[$__i__] ) {
                return;
            } 
        }
        self::callRoute($route, $callback);
        exit();
    }

    public static function callRoute($route, $callback)
    {
        if (is_array($callback)) {
            if (is_string($callback[0]) && is_string($callback[1]) && count($callback) == 2) {
                // Instantiate class and call method
                $class_name = $callback[0];
                $method_name = $callback[1];
                $fully_qualified_class_name = "\\$class_name";
                $obj = new $fully_qualified_class_name;
                call_user_func( [$obj, $method_name],  $callback[1]);
                $ValidRoute = $route;
            } 
        }
        else if (is_callable($callback)) {
            $callback->__invoke();
            $ValidRoute = $route;
        }
    }

    public static function checkRoute()
    {
        global $ValidRoute;
        if ($ValidRoute == null) {
            require_once('./app/views/404.php');
        }
    }

    public static function get($route, $callback)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            self::set($route, $callback);
        } 
    }

    public static function post($route, $callback)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            self::set($route, $callback);
        } 
    }

    public static function patch($route, $callback)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
            self::set($route, $callback);
        } 
    }

    public static function put($route, $callback)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            self::set($route, $callback);
        } 
    }

    public static function delete($route, $callback)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            self::set($route, $callback);
        } 
    }
}
