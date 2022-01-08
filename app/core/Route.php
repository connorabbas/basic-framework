<?php
class Route
{
    public static function set($route, $closure)
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
        if( $route_parts[0] == '' && count($request_url_parts) == 0 ){
            self::callRoute($route, $closure);
            exit();
        }

        if( count($route_parts) != count($request_url_parts) ){ return; } 
    
        // If multi component route
        $parameters = [];
        for( $__i__ = 0; $__i__ < count($route_parts); $__i__++ ){
            $route_part = $route_parts[$__i__];
            if( preg_match("/^[$]/", $route_part) ){
                $route_part = ltrim($route_part, '$');
                array_push($parameters, $request_url_parts[$__i__]);
                $$route_part=$request_url_parts[$__i__];
                // Set dynamic route variables for get or post
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $_POST[$route_part] = $$route_part;
                } else{
                    $_GET[$route_part] = $$route_part;
                }
            }
            else if( $route_parts[$__i__] != $request_url_parts[$__i__] ){
                return;
            } 
        }
        self::callRoute($route, $closure);
        exit();
    }

    public static function callRoute($route, $closure)
    {
        if(is_array($closure)){
            if (is_string($closure[0])) {
                // Statically
                if (strpos($closure[0], '::') !== false) {
                    call_user_func_array($closure[0], $closure[1]);
                }
                // Instantiate class and call method
                if (strpos($closure[0], '()->') !== false)  {
                    $closureParts = explode('()->', $closure[0]);
                    $class_name = $closureParts[0];
                    $method_name = $closureParts[1];
                    $fully_qualified_class_name = "\\$class_name";
                    $obj = new $fully_qualified_class_name;
                    call_user_func( [$obj, $method_name],  $closure[1]);
                }
            } else {
                // invoke regular function from route file
                $closure[0]->__invoke($closure[1]);
            }
            $ValidRoute = $route;
        }
    }

    public static function checkRoute()
    {
        global $ValidRoute;
        if($ValidRoute == null){
            App::view('404');
        }
    }

    public static function get($route, $closure)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            self::set($route, $closure);
        } 
    }

    public static function post($route, $closure)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            self::set($route, $closure);
        } 
    }
}
