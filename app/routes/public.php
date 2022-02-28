<?php
// Valid Routes for site

$routes->get('/', function(){
    return App::view('home');
});

$routes->checkRoute();
