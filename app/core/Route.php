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
            if(is_array($closure)){
                call_user_func_array($closure[0], $closure[1]);
            } else if (is_callable($closure)){
                $closure->__invoke();
            }
            $ValidRoute = $route;
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
                $_GET[$route_part] = $$route_part;
            }
            else if( $route_parts[$__i__] != $request_url_parts[$__i__] ){
                return;
            } 
        }
        if(is_array($closure)){
            call_user_func_array($closure[0], $closure[1]);
        } else if (is_callable($closure)){
            $closure->__invoke();
        }
        $ValidRoute = $route;
        exit();
    }

    public static function checkRoute()
    {
        global $ValidRoute;
        if($ValidRoute == null){
            require_once('./app/views/404.php');
            die($_404);
        }
    }
}
