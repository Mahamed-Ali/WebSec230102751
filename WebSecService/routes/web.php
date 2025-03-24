<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\ProductsController;
use App\Http\Controllers\Web\UsersController;
use App\Http\Controllers\Web\GradesController;

Route::get('register', [UsersController::class, 'register'])->name('register');
Route::post('register', [UsersController::class, 'doRegister'])->name('do_register');
Route::get('login', [UsersController::class, 'login'])->name('login');
Route::post('login', [UsersController::class, 'doLogin'])->name('do_login');
Route::get('logout', [UsersController::class, 'doLogout'])->name('do_logout');
Route::get('users', [UsersController::class, 'list'])->name('users');
Route::get('profile/{user?}', [UsersController::class, 'profile'])->name('profile');
Route::get('users/edit/{user?}', [UsersController::class, 'edit'])->name('users_edit');
Route::post('users/save/{user}', [UsersController::class, 'save'])->name('users_save');
Route::get('users/delete/{user}', [UsersController::class, 'delete'])->name('users_delete');
Route::get('users/edit_password/{user?}', [UsersController::class, 'editPassword'])->name('edit_password');
Route::post('users/save_password/{user}', [UsersController::class, 'savePassword'])->name('save_password');



Route::get('products', [ProductsController::class, 'list'])->name('products_list');
Route::get('products/edit/{product?}', [ProductsController::class, 'edit'])->name('products_edit');
Route::post('products/save/{product?}', [ProductsController::class, 'save'])->name('products_save');
Route::get('products/delete/{product}', [ProductsController::class, 'delete'])->name('products_delete');

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


Route::get('grades', [GradesController::class, 'list'])->name('grades_list');
Route::get('grades/add', [GradesController::class, 'add'])->name('grades_add');
Route::get('grades/edit/{grade}', [GradesController::class, 'edit'])->name('grades_edit');
Route::post('grades/save/{grade?}', [GradesController::class, 'save'])->name('grades_save');
Route::get('grades/delete/{grade}', [GradesController::class, 'delete'])->name('grades_delete');


