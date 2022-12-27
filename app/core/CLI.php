<?php

namespace App\Core;

class CLI
{
    public function createController($name)
    {
        if (!file_exists("app/controllers/" . $name . ".php")) {
            $nameSpace = '';
            $dirParts = explode('/', $name);
            $className = array_pop($dirParts);
            if (count($dirParts) >= 1) {
                $newDir = implode('/', $dirParts);
                array_unshift($dirParts, '');
                $nameSpace = implode('\\', $dirParts);
                if (!is_dir("app/controllers/" . $newDir)) {
                    mkdir("app/controllers/" . $newDir, 0777, true) or die("Unable to create directory!");       
                }
            }

            $content = "";
            $content .= "<?php" . PHP_EOL;
            $content .= PHP_EOL;
            $content .= "namespace App\Controllers" . $nameSpace . ";" . PHP_EOL;
            $content .= PHP_EOL;
            $content .= "class " . $className . PHP_EOL;
            $content .= "{" . PHP_EOL;
            $content .= "    public function __construct()" . PHP_EOL;
            $content .= "    {" . PHP_EOL;
            $content .= "        //" . PHP_EOL;
            $content .= "    }" . PHP_EOL;
            $content .= PHP_EOL;
            $content .= "    public function index()" . PHP_EOL;
            $content .= "    {" . PHP_EOL;
            $content .= "        //" . PHP_EOL;
            $content .= "    }" . PHP_EOL;
            $content .= "}" . PHP_EOL;

            // Write to the file
            $controllerFile = fopen("app/controllers/" . $name . ".php", "w") or die("Unable to open file!");
            fwrite($controllerFile, $content);
            fclose($controllerFile);

            // Output Success
            print_r("Controller: app/controllers/" . $name . ".php created!");
        } else {
            // Output Failure
            print_r("FAILED. app/controllers/" . $name . ".php already exists.");
        }
    }

    public function createModel($name)
    {
        if (!file_exists("app/models/" . $name . ".php")) {
            $nameSpace = '';
            $dirParts = explode('/', $name);
            $className = array_pop($dirParts);
            if (count($dirParts) >= 1) {
                $newDir = implode('/', $dirParts);
                array_unshift($dirParts, '');
                $nameSpace = implode('\\', $dirParts);
                if (!is_dir("app/models/" . $newDir)) {
                    mkdir("app/models/" . $newDir, 0777, true) or die("Unable to create directory!");       
                }
            }

            $content = "";
            $content .= "<?php" . PHP_EOL;
            $content .= PHP_EOL;
            $content .= "namespace App\Models" . $nameSpace . ";" . PHP_EOL;
            $content .= PHP_EOL;
            $content .= "use App\Core\Model;" . PHP_EOL;
            $content .= PHP_EOL;
            $content .= "class " . $className . " extends Model" . PHP_EOL;
            $content .= "{" . PHP_EOL;
            $content .= "    public function modelMethod()" . PHP_EOL;
            $content .= "    {" . PHP_EOL;
            $content .= "        //" . PHP_EOL;
            $content .= "    }" . PHP_EOL;
            $content .= "}" . PHP_EOL;

            // Write to the file
            $modelFile = fopen("app/models/" . $name . ".php", "w") or die("Unable to open file!");
            fwrite($modelFile, $content);
            fclose($modelFile);

            // Output Success
            print_r("Model: app/models/" . $name . ".php created!");
        } else {
            // Output Failure
            print_r("FAILED. app/models/" . $name . ".php already exists.");
        }
    }

    /**
     * Create env file on composer install script
     */
    public static function createEnvFile()
    {
        $source = './.env.example'; 
        $destination = './.env'; 

        // only create the file if it doesn't exist (initial install)
        if (!file_exists($destination)) {
            copy($source, $destination);
        }
    }
}
