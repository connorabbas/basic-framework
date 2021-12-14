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
        require_once('./app/views/' . $view . '.php');
    }
}
