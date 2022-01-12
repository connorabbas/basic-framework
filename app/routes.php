<?php
// Valid Routes for site

Route::get('/', function(){
    return App::view('root', ['pageTitle' => 'Home']);
});

Route::get('/tester', [TestController::class, 'index']);
Route::post('/tester', [TestController::class, 'postTest']);

Route::get('/dynamic/$tester', function(){
    echo 'test route without a controller class & dynamic GET data <br>';
    echo 'dynamic slug: '.$_GET['tester'];
});

Route::checkRoute();
