<?php
class CLI
{
    public function createController($name)
    {
        if (!file_exists("app/controllers/".$name.".php")) {
            $controllerFile = fopen("app/controllers/".$name.".php", "w") or die("Unable to open file!");
            $content = "";
            $content .= "<?php";
            $content .= "\n";
            $content .= "class ".$name." extends SiteController";
            $content .= "\n";
            $content .= "{";
            $content .= "\n";
            $content .= "    protected \$db;";
            $content .= "\n";
            $content .= "\n";
            $content .= "    public function __construct(\$db)";
            $content .= "\n";
            $content .= "    {";
            $content .= "\n";
            $content .= "        \$this->db = \$db;";
            $content .= "\n";
            $content .= "    }";
            $content .= "\n";
            $content .= "\n";
            $content .= "    public function index()";
            $content .= "\n";
            $content .= "    {";
            $content .= "\n";
            $content .= "    }";
            $content .= "\n";
            $content .= "}";
        
            // Write to the file
            fwrite($controllerFile, $content);
            fclose($controllerFile);
        
            // Output Success
            print_r("Controller: app/controllers/".$name.".php created!");
        } else {
            // Output Failure
            print_r("FAILED. app/controllers/".$name.".php already exists.");
        }
    }

    public function createModel($name)
    {
        if (!file_exists("app/models/".$name.".php")) {
            $modelFile = fopen("app/models/".$name.".php", "w") or die("Unable to open file!");
            $content = "";
            $content .= "<?php";
            $content .= "\n";
            $content .= "class ".$name;
            $content .= "\n";
            $content .= "{";
            $content .= "\n";
            $content .= "    protected \$db;";
            $content .= "\n";
            $content .= "\n";
            $content .= "    public function __construct(\$db)";
            $content .= "\n";
            $content .= "    {";
            $content .= "\n";
            $content .= "        \$this->db = \$db;";
            $content .= "\n";
            $content .= "    }";
            $content .= "\n";
            $content .= "\n";
            $content .= "    public function modelFunction()";
            $content .= "\n";
            $content .= "    {";
            $content .= "\n";
            $content .= "    }";
            $content .= "\n";
            $content .= "}";

            // Write to the file
            fwrite($modelFile, $content);
            fclose($modelFile);

            // Output Success
            print_r("Model: app/models/".$name.".php created!");
        } else {
            // Output Failure
            print_r("FAILED. app/models/".$name.".php already exists.");
        }
    }
}
