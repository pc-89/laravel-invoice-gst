<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;

// Route::get('/', function () {
//     return redirect('/login');
// });

Route::get('/login', [LoginController::class, 'login_page']);
Route::post('/login', [LoginController::class, 'login_check'])->name('login');

Route::get('/register', [LoginController::class, 'register_page']);
Route::post('/register', [LoginController::class, 'register_store'])->name('register');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// 🔒 Authenticated Routes Group (All logged-in users enter here)
Route::middleware(['auth'])->group(function () {

    // Global check: Admins visiting these pages get bounced to /dashboard by the middleware
    Route::middleware(['admin'])->group(function () {
         // Dedicated Dashboard Only for Admins (type 1)
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });


        Route::get('/invoice/create', [InvoiceController::class, 'create'])->name('invoice.create');
        Route::post('/invoice/store', [InvoiceController::class, 'store'])->name('invoice.store');
        Route::get('/invoice/{id}', [InvoiceController::class, 'show'])->name('invoice.show');
        Route::get('/invoice/{id}/pdf', [InvoiceController::class, 'pdf'])->name('invoice.pdf');
        Route::get('/invoice/{id}/excel', [InvoiceController::class, 'excel'])->name('invoice.excel');


});
