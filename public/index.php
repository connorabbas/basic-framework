<?php

/**
 * Basic PHP Framework
 * Developed and maintained by: Connor Abbas
 * Docs: https://github.com/connorabbas/basic-framework#documentation
 */

use App\Core\App;
use Dotenv\Dotenv;
use App\Core\Router;
use App\Core\Container;
use Dotenv\Exception\InvalidPathException;

// Composer autoload
require __DIR__ . '/../vendor/autoload.php';

// Register .env data into $_ENV super global
try {
    $dotenv = Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();
} catch (InvalidPathException) {
    echo '.env file not configured for site.';
    die;
}

// Error Reporting
if ($_ENV['ENV'] == 'local') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// Run the site
$container = new Container();
$router = new Router($container);
$app = new App($container, $router);
$app->containerSetup()->run();
