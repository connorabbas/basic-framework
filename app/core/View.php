<?php

namespace App\Core;

use League\Plates\Engine;

class View
{
    public static function show($view, $data = [])
    {
        // View templates using Plates: https://platesphp.com/
        if (file_exists('../app/views/' . $view . '.php')) {
            $templates = new Engine('../app/views/');
            $templates->addFolder('template', '../app/views/templates/');
            echo $templates->render($view, $data);
        } 
        // Not found
        else {
            require_once('../app/views/pages/404.php');
        } 
    }
}
