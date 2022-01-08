<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ENV and global vars
require_once('./env.php');
require_once('./app/globals.php');

// Autoload Classes
spl_autoload_register(
    function ($class) {
        if (file_exists('./app/core/' . $class . '.php')) {
            require_once './app/core/' . $class . '.php';
        } else if (file_exists('./app/controllers/' . $class . '.php')) {
            require_once './app/controllers/' . $class . '.php';
        } else if (file_exists('./app/models/' . $class . '.php')) {
            require_once './app/models/' . $class . '.php';
        }
    }
);

// Include DB connection once, use dependency injection with class constructors for DB usage
$db = new DB();

// Routes for site
require_once('./app/routes.php');
