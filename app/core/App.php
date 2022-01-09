<?php
class App
{
    public static function view($view, $data = [], $template = 'main')
    {
        if ($template != null && file_exists('./app/views/templates/' . $template . '.php') && file_exists('./app/views/' . $view . '.php')) {
            // Create variables for view
            if(count($data)){
                foreach($data as $var => $val){
                    ${$var} = $val;
                }
            }
            // Include template and view file
            require_once('./app/views/templates/' . $template . '.php');
        } else {
            require_once('./app/views/404.php');
        } 
    }

    public static function route($path)
    {
        $path = ltrim($path, '/');
        return BASE_DIR . $path;
    }
}
