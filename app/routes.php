<?php
// Valid Routes for site

$routes->get('/', function(){
    return App::view('home', [
        'pageTitle' => 'Home',
        'pageDesc' => 'Welcome to the php mini framework!',
    ]);
});

$routes->get('/tester', [TestController::class, 'index']);
$routes->post('/tester', [TestController::class, 'postTest']);

$routes->get('/dynamic/$tester', function(){
    echo 'test route without a controller class & dynamic GET data <br>';
    echo 'dynamic slug: '.$_GET['tester'];
});

$routes->checkRoute();
