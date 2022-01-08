<?php
class App
{
    public static function view($view, $data=array())
    {
        if(count($data)){
            foreach($data as $var => $val){
                ${$var} = $val;
            }
        }
        // Include view file
        if(file_exists('./app/views/' . $view . '.php')){
            require_once('./app/views/' . $view . '.php');
        } else{
            App::view('404');
        }
    }

    public static function route($path)
    {
        $path = ltrim($path, '/');
        echo BASE_DIR . $path;
    }
}
