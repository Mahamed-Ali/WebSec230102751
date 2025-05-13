<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\UsersController;
use App\Http\Controllers\Web\ProductsController;
use Illuminate\Http\Request;


// Static Pages
Route::get('/', function () {
    $email = emailFromLoginCertificate();
    if($email && !auth()->user()) {
    $user = User::where('email', $email)->first();
    if($user) Auth::login($user);
    }
    return view('welcome');
});

Route::get('/even', function () {
    return view('even');
});
Route::get('/prime', function () {
    return view('prime');
});
Route::get('/multable/{number?}', function ($number = 10) {
    $j = $number;
    return view('multable', compact("j"));
});
Route::get('/minitest', function () {
    $bills = [
        ['item' => 'Apples', 'quantity' => 2, 'price' => 3.50],
        ['item' => 'Bread', 'quantity' => 1, 'price' => 2.00],
        ['item' => 'Milk', 'quantity' => 1, 'price' => 2.75],
        ['item' => 'Cheese', 'quantity' => 1, 'price' => 5.00],
    ];
    return view('minitest', compact("bills"));
});

// Web Authentication
Route::get('register', [UsersController::class, 'register'])->name('register');
Route::post('register', [UsersController::class, 'doRegister'])->name('do_register');
Route::get('login', [UsersController::class, 'login'])->name('login');
Route::post('login', [UsersController::class, 'doLogin'])->name('do_login');
Route::get('logout', [UsersController::class, 'doLogout'])->name('do_logout');

// Users Management
Route::get('users', [UsersController::class, 'list'])->name('users');
Route::get('users/edit/{user?}', [UsersController::class, 'edit'])->name('users_edit');
Route::post('users/save/{user}', [UsersController::class, 'save'])->name('users_save');
Route::get('users/delete/{user}', [UsersController::class, 'delete'])->name('users_delete');
Route::get('profile/{user?}', [UsersController::class, 'profile'])->name('profile');
Route::get('users/edit_password/{user?}', [UsersController::class, 'editPassword'])->name('edit_password');
Route::post('users/save_password/{user}', [UsersController::class, 'savePassword'])->name('save_password');

Route::get('/admin/employees/create', [UsersController::class, 'createEmployee'])->name('create_employee')->middleware('role:admin');
Route::post('/admin/employees/store', [UsersController::class, 'storeEmployee'])->name('store_employee')->middleware('role:admin');

// Employee Features
Route::get('users/customers', [UsersController::class, 'listCustomers'])->name('list_customers');
Route::get('users/add_credit/{user}', [UsersController::class, 'addCredit'])->name('add_credit');
Route::post('users/save_credit/{user}', [UsersController::class, 'saveCredit'])->name('save_credit');
Route::post('/users/{id}/reset-credit', [UsersController::class, 'resetCredit'])->name('users.resetCredit')->middleware('can:update_credit');

// Products Management
Route::get('products', [ProductsController::class, 'list'])->name('products_list');
Route::get('products/edit/{product?}', [ProductsController::class, 'edit'])->name('products_edit');
Route::post('products/save/{product?}', [ProductsController::class, 'save'])->name('products_save');
Route::get('products/delete/{product}', [ProductsController::class, 'delete'])->name('products_delete');

Route::get('purchases', [ProductsController::class, 'myPurchases'])->name('products_purchases');

Route::get('products/buy/{product}', [ProductsController::class, 'confirm'])->name('products_buy_confirm');
Route::post('products/buy/{product}', [ProductsController::class, 'buy'])->name('products_buy');



Route::get('users/{user}/credit', [UsersController::class, 'addCredit'])->name('add_credit');
Route::post('users/{user}/credit', [UsersController::class, 'saveCredit'])->name('save_credit');

Route::get('verify', [UsersController::class, 'verify'])->name('verify');


Route::get('/forgot-password', [App\Http\Controllers\Web\UsersController::class, 'forgotPasswordPage'])->name('password.request');

Route::post('/forgot-password', [App\Http\Controllers\Web\UsersController::class, 'sendTemporaryPassword'])->name('password.email');

Route::get('/change-password', [App\Http\Controllers\Web\UsersController::class, 'changePasswordPage'])->name('password.change');
Route::post('/change-password', [App\Http\Controllers\Web\UsersController::class, 'saveNewPassword'])->name('password.update');

Route::get('/forgot-password', [App\Http\Controllers\Web\UsersController::class, 'forgotPasswordPage'])->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\Web\UsersController::class, 'sendResetLink'])->name('password.email');

Route::get('/reset-password', [App\Http\Controllers\Web\UsersController::class, 'resetPasswordPage'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\Web\UsersController::class, 'resetPasswordSave'])->name('password.update');


Route::get('/auth/google',[UsersController::class, 'redirectToGoogle'])->name('login_with_google');
Route::get('/auth/google/callback',[UsersController::class, 'handleGoogleCallback']);

Route::get('/auth/facebook',[UsersController::class, 'redirectToFacebook'])->name('login_with_facebook');
Route::get('/auth/facebook/callback',[UsersController::class, 'handleFacebookCallback']);

Route::get('/auth/microsoft', [UsersController::class, 'redirectToMicrosoft'])->name('login_with_microsoft');
Route::get('/auth/microsoft/callback', [UsersController::class, 'handleMicrosoftCallback']);





Route::get('/sqli', function () {
    DB::statement("DROP TABLE IF EXISTS " . request()->table);
});


Route::get('/xss', function () {
    return view('xss', ['keywords' => request()->keywords]);
});


Route::post('/add-product', function(Request $request){
    DB::table('products')->insert(['name' => $request->name]);
});



Route::get('/collect', function (Request $request) {
    $name = $request->query('name');
    $credit = $request->query('credit');

    \Log::info("Collected Name: $name, Credit: $credit");

    return response()->json(['status' => 'received']);
});

Route::get('/test-endpoint', function () {
    return 'The endpoint is working!';
});

Route::get('/collect', [\App\Http\Controllers\Web\UsersController::class, 'collect']);




Route::get('/', function () {
    $email = emailFromLoginCertificate();
    if($email && !auth()->user()) {
    $user = User::where('email', $email)->first();
    if($user) Auth::login($user);
    }
    return view('welcome');
});




Route::get('/cryptography', function (Request $request) {
    $data = $request->data ?? "Welcome to Cryptography";
    $action = $request->action ?? "Encrypt";
    $result = "";
    $status = "Failed";

    if ($action == "Encrypt") {
        $temp = openssl_encrypt($data, 'aes-128-ecb', 'thisisasecretkey', OPENSSL_RAW_DATA, '');
        if ($temp) {
            $status = 'Encrypted Successfully';
            $result = base64_encode($temp);
        }
    } elseif ($action == "Decrypt") {
        $temp = base64_decode($data);
        $result = openssl_decrypt($temp, 'aes-128-ecb', 'thisisasecretkey', OPENSSL_RAW_DATA, '');
        if ($result) {
            $status = 'Decrypted Successfully';
        }
    } elseif ($action == "Hash") {
        $temp = hash('sha256', $request->data);
        $result = base64_encode($temp);
        $status = 'Hashed Successfully';
    } elseif ($action == "Sign") {
        $path = storage_path('app/private/useremail@domain.com.pfx');
        $password = '12345678';
        $certificates = [];
        $pfx = file_get_contents($path);
        openssl_pkcs12_read($pfx, $certificates, $password);
        $privateKey = $certificates['pkey'];
        $signature = '';
        if (openssl_sign($request->data, $signature, $privateKey, 'sha256')) {
            $result = base64_encode($signature);
            $status = 'Signed Successfully';
        }
    } elseif ($action == "Verify") {
    $signature = base64_decode($request->result);
    $path = storage_path('app/public/useremail@domain.com.crt');

    if (!file_exists($path)) {
        $status = 'Public certificate not found.';
    } else {
        $publicKey = file_get_contents($path);
        if (!$publicKey) {
            $status = 'Failed to read public key.';
        } elseif (openssl_verify($request->data, $signature, $publicKey, OPENSSL_ALGO_SHA256)) {
            $status = 'Verified Successfully';
        } else {
            $status = 'Verification Failed.';
        }
    }
    } elseif ($action == "KeySend") {
        $path = storage_path('app/public/useremail@domain.com.crt');
        $publicKey = file_get_contents($path);
        $temp = '';
        if (openssl_public_encrypt($request->data, $temp, $publicKey)) {
            $result = base64_encode($temp);
            $status = 'Key is Encrypted Successfully';
        }
    } elseif ($action == "KeyRecive") {
    $path = storage_path('app/private/useremail@domain.com.pfx');
    $password = '12345678';

    if (!file_exists($path)) {
        $status = 'PFX file not found.';
    } else {
        $certificates = [];
        $pfx = file_get_contents($path);

        if (!openssl_pkcs12_read($pfx, $certificates, $password)) {
            $status = 'Failed to read PFX. Check password.';
        } else {
            $privateKey = $certificates['pkey'];
            $encryptedKey = base64_decode($request->data);
            $result = '';

            if (openssl_private_decrypt($encryptedKey, $result, $privateKey)) {
                $status = 'Key is Decrypted Successfully';
            } else {
                $status = 'Key Decryption Failed';
            }
        }
    }
}

    return view('cryptography', compact('data', 'result', 'action', 'status'));
})->name('cryptography');