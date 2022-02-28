<?php
/*
|--------------------------------------------------------------------------
| Valid Routes for Site
|--------------------------------------------------------------------------
*/

$routes->get('/', function(){
    return App::view('welcome');
});

// Check if valid route
$routes->checkRoute();
