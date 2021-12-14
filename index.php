<?php
/* 
* based on code by: https://github.com/howCodeORG/how & https://phprouter.com/
*/

// Autoload Classes
spl_autoload_register(
    function ($class) {
        if (file_exists('./app/core/' . $class . '.php')) {
            require_once './app/core/' . $class . '.php';
        } else if (file_exists('./app/controllers/' . $class . '.php')) {
            require_once './app/controllers/' . $class . '.php';
        }
    }
);

// Include DB connection once, use dependency injection with class constructors for DB usage
require_once '../DATABASE/pdo_db.class.php';
global $db;
$db = new db();

// Global vars and routes
require_once('./app/globals.php');
require_once('./app/routes.php');
