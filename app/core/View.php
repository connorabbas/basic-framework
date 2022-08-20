<?php

namespace App\Core;

use League\Plates\Engine;

class View
{
    public static function show($view, $data = [])
    {
        $realPath = str_replace('.', '/', $view);

        // View templates using Plates: https://platesphp.com/
        if (file_exists('../app/views/' . $realPath . '.php')) {
            $templates = new Engine('../app/views/');
            $templates->addFolder('template', '../app/views/templates/');
            echo $templates->render($realPath, $data);
        } else {
            self::show('pages.404');
        } 
    }
}
