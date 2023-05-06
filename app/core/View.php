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
        $templates = new Engine(__DIR__ . '/../views');

        $realPath = str_replace('.', '/', $view);
        foreach (config('plates', 'folders') as $name => $folder) {
            $templates->addFolder($name, __DIR__ . '/../views' . $folder);
        }

        return $templates->render($realPath, $data);
    }
}
