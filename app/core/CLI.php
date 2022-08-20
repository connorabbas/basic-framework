<?php

namespace App\Core;

class CLI
{
    public function createController($name)
    {
        if (!file_exists("app/controllers/" . $name . ".php")) {
            $content = "";
            $content .= "<?php" . PHP_EOL;
            $content .= PHP_EOL;
            $content .= "namespace App\Controllers;" . PHP_EOL;
            $content .= PHP_EOL;
            $content .= "use App\Core\DB;" . PHP_EOL;
            $content .= PHP_EOL;
            $content .= "class ".$name."" . PHP_EOL;
            $content .= "{" . PHP_EOL;
            $content .= "    protected \$db;" . PHP_EOL;
            $content .= PHP_EOL;
            $content .= "    public function __construct()" . PHP_EOL;
            $content .= "    {" . PHP_EOL;
            $content .= "        \$this->db = new DB();" . PHP_EOL;
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
            $content = "";
            $content .= "<?php" . PHP_EOL;
            $content .= PHP_EOL;
            $content .= "namespace App\Models;" . PHP_EOL;
            $content .= PHP_EOL;
            $content .= "class ".$name." extends Model" . PHP_EOL;
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
