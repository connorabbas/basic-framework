<?php
class App
{
    public static function view($view, $data = [], $template = 'main')
    {
        global $pageTitle, $pageDesc;

        // start buffer
        ob_start();

        // Create variables for view
        if (count($data)) {
            foreach($data as $var => $val) {
                ${$var} = $val;
            }
        }
        // Only view
        if ($template == null && file_exists('./app/views/' . $view . '.php')) {
            // Include view file
            require_once('./app/views/' . $view . '.php');
        } 
        // View with template
        else if ($template != null && file_exists('./app/views/templates/' . $template . '.template.php') && file_exists('./app/views/' . $view . '.php')) {
            // Include template and view file
            require_once('./app/views/templates/' . $template . '.template.php');
        } 
        // Not found
        else {
            require_once('./app/views/404.php');
        } 

        // return content
        $viewContent = ob_get_clean();
        echo($viewContent);
    }

    public static function path($path)
    {
        $path = ltrim($path, '/');
        return BASE_DIR . $path;
    }

    public static function redirect($path)
    {
        $path = ltrim($path, '/');
        header("location: ".BASE_DIR.$path);
    }

    public static function set_csrf()
    {
        if( ! isset($_SESSION["csrf"]) ){ $_SESSION["csrf"] = bin2hex(random_bytes(50)); }
        return '<input type="hidden" name="csrf" value="'.$_SESSION["csrf"].'">';
    }

    public static function is_csrf_valid()
    {
        if( ! isset($_SESSION['csrf']) || ! isset($_POST['csrf'])){ return false; }
        if( $_SESSION['csrf'] != $_POST['csrf']){ return false; }
        return true;
    }
}