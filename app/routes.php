<?php
// Valid Routes for site
// :: to call static controller method
// ()-> to intantiate controller class and call method

Route::get('/', ['App::view', ['root']]);

Route::get('/tester', ['TestController()->index', [$db]]);
Route::post('/tester', ['TestController()->postTest', []]);

Route::get('/test/$tester', [function(){

    echo 'test route without a controller class & dynamic GET data';
    echo '<pre style="max-height:600px; overflow-y: auto; border:1px solid #000;">';
    var_dump($_GET);
    echo '</pre>';

}, []]);

Route::checkRoute();
