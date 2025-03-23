<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\ProductsController;
use App\Http\Controllers\Web\UsersController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/multable/{number?}', function ($number=10) {
    $j = $number??2;
    return view('multable',compact("j")); 
});

Route::get('/even', function () {
    return view('even'); 
});

Route::get('/prime', function () {
    return view('prime'); 
});

Route::get('/minitest', function () {
    $bills=[
        ['item' => 'Apples', 'quantity' => 2, 'price' => 3.50],
        ['item' => 'Bread', 'quantity' => 1, 'price' => 2.00],
        ['item' => 'Milk', 'quantity' => 1, 'price' => 2.75],
        ['item' => 'Cheese', 'quantity' => 1, 'price' => 5.00],
    ];
    return view('minitest',compact("bills")); 
});


Route::get('products', [ProductsController::class, 'list'])->name('products_list');
Route::get('products/edit/{product?}', [ProductsController::class, 'edit'])->name('products_edit');
Route::post('products/save/{product?}', [ProductsController::class, 'save'])->name('products_save');
Route::get('products/delete/{product}', [ProductsController::class, 'delete'])->name('products_delete');


Route::get('users', [UsersController::class, 'list'])->name('users_list');
Route::get('users/add', [UsersController::class, 'add'])->name('users_add');
Route::get('users/edit/{user}', [UsersController::class, 'edit'])->name('users_edit');
Route::post('users/save/{user?}', [UsersController::class, 'save'])->name('users_save');
Route::get('users/delete/{user}', [UsersController::class, 'delete'])->name('users_delete');