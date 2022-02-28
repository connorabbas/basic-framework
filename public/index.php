<?php
/*
|--------------------------------------------------------------------------
| PHP Mini Framework
|--------------------------------------------------------------------------
|   
| Developed and maintained by: Connor Abbas 
| Source code: https://github.com/connorabbas/php-mf
| Latest buid: v1.2
|
*/

// ENV and global vars
require_once('../app/vars/env.php');
require_once('../app/vars/globals.php');

// Report Errors
if (ENV == 'DEV' || ENV == 'STAGING') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// Composer autoloader for vendor classes (run composer update)
if (file_exists('../vendor/autoload.php')) {
    require '../vendor/autoload.php';
}

// Mini framework autoloader
require '../app/autoload.php';

// Invoke the site
$site = new SiteController();
$site->invoke();
