<?php

use App\Controllers\SiteController;

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

// Composer autoloader
if (file_exists('../vendor/autoload.php')) {
    require '../vendor/autoload.php';
}

// ENV and global vars
if (file_exists('../app/vars/env.php')) {
    require_once('../app/vars/env.php');
}
require_once('../app/vars/globals.php');

// Report Errors
if (ENV == 'local') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// Invoke the site
$site = new SiteController();
$site->invoke();
