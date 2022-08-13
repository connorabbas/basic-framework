<?php

use Dotenv\Dotenv;
use App\Controllers\SiteController;
use Dotenv\Exception\InvalidPathException;

/**
 * PHP Mini Framework
 * Developed and maintained by: Connor Abbas 
 * Source code: https://github.com/connorabbas/php-mini-framework#php-mini-framework
 */

// Composer autoload
if (file_exists('../vendor/autoload.php')) {
    require '../vendor/autoload.php';
}

// Register .env data into $_ENV super global
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
try {
    $dotenv->load();
} catch (InvalidPathException) {
    echo '.env file not configured for site.';
    die;
}

// Global helper functions and constants
require_once('../app/core/Helpers.php');
require_once('../app/data/Constants.php');

// Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Error Reporting
if (config('site.environment') == 'local') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// Invoke the site
$site = new SiteController();
$site->invoke();
