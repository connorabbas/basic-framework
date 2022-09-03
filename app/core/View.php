<?php

namespace App\Core;

use Exception;
use League\Plates\Engine;

class View
{
    public static function render($view, $data = [])
    {
        // View templates using Plates: https://platesphp.com/
        $realPath = str_replace('.', '/', $view);
        $templates = new Engine('../app/views/');
        foreach (config('plates_templates.folders') as $name => $folder) {
            $templates->addFolder($name, '../app/views/' . $folder);
        }
        
        return $templates->render($realPath, $data);
    }
}
