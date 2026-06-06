<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/purchases', [PurchaseController::class, 'index']);
    Route::get('/purchases/{purchase}', [PurchaseController::class, 'show']);

    Route::middleware(['role:admin'])->group(function () {
        Route::post('/purchases', [PurchaseController::class, 'store']);
        Route::match(['put', 'patch'], '/purchases/{purchase}', [PurchaseController::class, 'update']);
        Route::delete('/purchases/{purchase}', [PurchaseController::class, 'destroy']);
        Route::post('/purchases/import-legacy', [PurchaseController::class, 'importLegacy']);
    });
});