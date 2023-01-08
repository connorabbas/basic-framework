<?php

namespace App\Core;

use League\Plates\Engine;

class View
{
    /**
     * Return the rendered view content
     * View templates using Plates: https://platesphp.com/
     */
    public static function render(string $view, array $data = [])
    {
        $templates = new Engine('../app/views/');

        $realPath = str_replace('.', '/', $view);
        foreach (config('plates_templates.folders') as $name => $folder) {
            $templates->addFolder($name, '../app/views/' . $folder);
        }

        return $templates->render($realPath, $data);
    }
}
