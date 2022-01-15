<?php
class App
{
    public static function view($view, $data = [], $template = 'main')
    {
        global $pageTitle, $pageDesc;

        // Create variables for view
        if (count($data)) {
            foreach($data as $var => $val) {
                ${$var} = $val;
            }
        }

        if ($template == null && file_exists('./app/views/' . $view . '.php')) {
            // Include view file
            require_once('./app/views/' . $view . '.php');
        } else if ($template != null && file_exists('./app/views/templates/' . $template . '.template.php') && file_exists('./app/views/' . $view . '.php')) {
            // Include template and view file
            require_once('./app/views/templates/' . $template . '.template.php');
        } else {
            require_once('./app/views/404.php');
        } 
    }

    public static function route($path)
    {
        $path = ltrim($path, '/');
        return BASE_DIR . $path;
    }

    public static function redirect($path)
    {
        $path = ltrim($path, '/');
        header("location: ".BASE_DIR.$path);
    }
}