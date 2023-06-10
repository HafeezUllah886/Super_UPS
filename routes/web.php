<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\productController;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [AuthController::class, "index"])->name('login');
Route::post('/', [AuthController::class, "signin"]);

Route::middleware('auth')->group(function (){
    Route::get('/dashboard', [dashboardController::class, "dashboard"]);
    Route::get('/addProduct', [productController::class, "add"]);

    Route::get('/category', [productController::class, "category"]);
    Route::post('/category', [productController::class, "storeCat"]);
    Route::post('/category/edit', [productController::class, "editCat"]);

    Route::get('/company', [productController::class, "company"]);
    Route::post('/company', [productController::class, "storeCoy"]);
    Route::post('/company/edit', [productController::class, "editCoy"]);

    Route::get('/products', [productController::class, "products"]);
    Route::post('/products', [productController::class, "storePro"]);
    Route::post('/product/edit', [productController::class, "editPro"]);
    Route::get('/product/delete/{id}', [productController::class, "deletePro"]);
    Route::get('/products/trashed', [productController::class, "trashedPro"]);
    Route::get('/product/restore/{id}', [productController::class, "restorePro"]);

    Route::get('/accounts', [AccountController::class, "accounts"]);
    Route::post('/accounts', [AccountController::class, "storeAccount"]);
    Route::get('/account/delete/{id}', [AccountController::class, "deleteAccount"]);
    Route::get('/accounts/statement/{id}', [AccountController::class, "statementView"]);
    Route::get('/accounts/details/{id}/{from}/{to}', [AccountController::class, "details"]);

    Route::get('/deposit', [AccountController::class, "deposit"]);
    Route::post('/deposit', [AccountController::class, "storeDeposit"]);
    Route::get('/deposit/delete/{ref}', [AccountController::class, "deleteDeposit"]);

    Route::get('/withdraw', [AccountController::class, "withdraw"]);
    Route::post('/withdraw', [AccountController::class, "storeWithdraw"]);
    Route::get('/withdraw/delete/{ref}', [AccountController::class, "deleteWithdraw"]);

});
