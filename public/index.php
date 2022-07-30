<?php

use Dotenv\Dotenv;
use App\Controllers\SiteController;

/**
 * PHP Mini Framework
 * Developed and maintained by: Connor Abbas 
 * Source code: https://github.com/connorabbas/php-mini-framework#php-mini-framework
 */

// Composer autoloader
if (file_exists('../vendor/autoload.php')) {
    require '../vendor/autoload.php';
}

// ENV and global vars
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();
require_once('../globals.php');

// Global helper functions
require_once('../app/core/Helpers.php');

// Report Errors
if (ENV == 'local') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// Invoke the site
$site = new SiteController();
$site->invoke();
