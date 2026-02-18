<?php

use Illuminate\Support\Facades\Route;

Route::get('/gis-app', function () {
    return view('gis_app');
});

Route::get('/', function(){
    return view('welcome');
});
