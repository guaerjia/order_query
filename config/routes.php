<?php
use TinyLara\TinyRoute\TinyRoute as Route;

Route::get('', 'HomeController@home');

Route::get('/foo', function() {
   echo "Foo!";
});

Route::get('/test', 'TestController@home');

Route::get('/test2/nima', function(){

    echo "Shit!";
});

Route::error(function(){
    throw new Exception("404 Not Found");
});

Route::dispatch('View@process');