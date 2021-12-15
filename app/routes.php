<?php
/* 
  Valid Routes for site
*/

Route::set('/', ['App::view', ['root']]);

Route::set('/tester', ['TestController::index', [$db]]);

Route::set('/no-controller', [function($test){

    echo 'test route without a controller function used.';
    echo '<pre style="max-height:600px; overflow-y: auto; border:1px solid #000;">';
    var_dump($test);
    echo '</pre>';

}, ['test_data']]);

Route::set('/test/$tester', [function(){

    echo 'test route to show dynamic GET data';
    echo '<pre style="max-height:600px; overflow-y: auto; border:1px solid #000;">';
    var_dump($_GET);
    echo '</pre>';

}, []]);

Route::checkRoute();
