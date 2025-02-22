<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/multable/{number?}', function ($number=10) {
    $j = $number;
    return view('multable',compact("j")); 
});

Route::get('/even', function () {
    return view('even'); 
});

Route::get('/prime', function () {
    return view('prime'); 
});
   
