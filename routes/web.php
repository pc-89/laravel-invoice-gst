<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController; // 🌟 Import your new Admin Controller
use App\Http\Controllers\CompanyDetailController;

// ── PUBLIC AUTHENTICATION ROUTES ─────────────────────────────────────
Route::get('/login', [LoginController::class, 'login_page']);
Route::post('/login', [LoginController::class, 'login_check'])->name('login');

Route::get('/register', [LoginController::class, 'register_page']);
Route::post('/register', [LoginController::class, 'register_store'])->name('register');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// ── PROTECTED BASELINE ROUTE GROUP (Logged-in Users Only) ──────────────
Route::middleware(['auth'])->group(function () {

    // 1. 🌟 The Waiting Screen: Where unapproved users land safely
    Route::get('/approval-notice', function () {
        return view('auth.approval-notice');
    })->name('approval.notice');

    // 2. 🌟 ADMIN ONLY ROUTE GROUP (Role must be 'admin')
    Route::middleware(['admin'])->group(function () {
        // Main Admin Approval Control panel dashboard
        // Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::post('/admin/users/{id}/approve', [AdminController::class, 'approve'])->name('admin.users.approve');

        // Your legacy global admin dashboard endpoint fallback
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });

    // 3. 🌟 APPROVED USER ONLY ROUTE GROUP (User must be verified/approved by admin)
    Route::middleware(['approved'])->group(function () {
        // User Profiles Dashboard Home
        Route::get('/user/dashboard', [CompanyDetailController::class, 'index'])->name('user.dashboard');
        Route::post('/user/company/save', [CompanyDetailController::class, 'store'])->name('user.company.save');

        Route::get('/invoice/create', [InvoiceController::class, 'create'])->name('invoice.create');
        Route::post('/invoice/store', [InvoiceController::class, 'store'])->name('invoice.store');
        Route::get('/invoice/{id}', [InvoiceController::class, 'show'])->name('invoice.show'); // upto this working fine
        Route::get('/invoice/{id}/pdf', [InvoiceController::class, 'pdf'])->name('invoice.pdf'); // not working
        Route::get('/invoice/{id}/excel', [InvoiceController::class, 'excel'])->name('invoice.excel'); // not working
    });

});
