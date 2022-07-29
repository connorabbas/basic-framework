<?php

// Globals
$URL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$pageTitle = 'Welcome!';
$pageDesc = "This is a mini framework with the basic php application essentials";

// Constants
if (!$_ENV['ENV']) {
    define('ENV', 'local');
} else {
    define('ENV', $_ENV['ENV']);
}
define('URL', $URL);
