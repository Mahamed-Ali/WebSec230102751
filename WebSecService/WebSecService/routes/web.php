<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\billController;
use App\Http\Controllers\Web\ProductsController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\Web\UsersController;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;

Route::get('products', [ProductsController::class, 'list'])->name('products_list');
Route::get('products/edit/{product?}', [ProductsController::class, 'edit'])->name('products_edit');
Route::post('products/save/{product?}', [ProductsController::class, 'save'])->name('products_save');
Route::get('products/delete/{product}', [ProductsController::class, 'delete'])->name('products_delete');

Route::get('/exam', [ExamController::class, 'index'])->name('exam.index');
Route::post('/exam/submit', [ExamController::class, 'submit'])->name('exam.submit');

Route::get('register', [UsersController::class, 'register'])->name('register');
Route::post('register', [UsersController::class, 'doRegister'])->name('do_register');
Route::get('login', [UsersController::class, 'login'])->name('login');
Route::post('login', [UsersController::class, 'doLogin'])->name('do_login');
Route::get('logout', [UsersController::class, 'doLogout'])->name('do_logout');

Route::get('profile', [UsersController::class, 'profile'])->name('profile');
Route::post('profile/update-password', [UsersController::class, 'updatePassword'])->name('profile.update_password');

Route::get('users/edit/{user?}', [UsersController::class, 'edit'])->name('users_edit');

Route::post('users/save/{user}', [UsersController::class, 'save'])->name('users_save');

Route::get('users', [UsersController::class, 'index'])->name('users.index');
Route::get('users/create', [UsersController::class, 'create'])->name('users.create');
Route::post('users', [UsersController::class, 'store'])->name('users.store');
Route::get('users/{user}/edit', [UsersController::class, 'edit'])->name('users.edit');
Route::put('users/{user}', [UsersController::class, 'update'])->name('users.update');
Route::delete('users/{user}', [UsersController::class, 'destroy'])->name('users.destroy');

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/even', function () {
    return view('even');
})->name('even');

Route::get('/prime', function () {
    return view('prime');
})->name('prime');

Route::get('/multable', function () {
    return view('multable');
})->name('multable');

Route::get('/bill', [billController::class, 'showBill'])->name('bill');

Route::get('/TrancScript', function () {
    return view('transcript');
})->name('TrancScript');

Route::get('/user', function () {
    return view('user');
})->name('user');

Route::get('/grades', function () {
    return view('grades');
})->name('grades');

Route::get('products/list', [ProductsController::class, 'list'])->name('products.list');

Route::middleware(['auth'])->group(function () {
    Route::middleware(['permission:view_users'])->get('/users', [UserController::class, 'index'])->name('users.index');
    Route::middleware(['permission:edit_users'])->post('/users', [UserController::class, 'store'])->name('users.store');
    Route::middleware(['permission:edit_users'])->put('/users/{id}', [UserController::class, 'update']);
    Route::middleware(['permission:change_password'])->put('/users/{id}/password', [UserController::class, 'changePassword']);
    Route::middleware(['permission:delete_users'])->delete('/users/{id}', [UserController::class, 'destroy']);
    
    Route::middleware(['permission:view_profile'])->get('/profile', [UserController::class, 'showProfile'])->name('profile');
    Route::middleware(['permission:edit_profile'])->put('/profile', [UserController::class, 'updateProfile']);
});

Route::middleware(['auth'])->group(function () {
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
    });

    Route::middleware(['permission:view_profile'])->get('/profile', [UserController::class, 'showProfile']);
    Route::middleware(['permission:edit_profile'])->put('/profile', [UserController::class, 'updateProfile']);
});

Route::middleware(['auth', 'permission:edit_users'])->group(function () {
    // Your protected routes here
});

Route::middleware(['auth'])->group(function () {
    // User profile routes
    Route::get('/profile', [UsersController::class, 'profile'])->name('profile');
    Route::put('/profile/update', [UsersController::class, 'updateProfile'])->name('profile.update');
    
    // Admin routes protected by role middleware
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UsersController::class);
    });
});

Route::middleware(['auth'])->group(function () {
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::patch('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
});

?>
