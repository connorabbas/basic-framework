<?php
// Valid Routes for site

$route->get('/', function(){
    return App::view('root', ['pageTitle' => 'Home']);
});

$route->get('/tester', [TestController::class, 'index']);
$route->post('/tester', [TestController::class, 'postTest']);

$route->get('/dynamic/$tester', function(){
    echo 'test route without a controller class & dynamic GET data <br>';
    echo 'dynamic slug: '.$_GET['tester'];
});

$route->checkRoute();
