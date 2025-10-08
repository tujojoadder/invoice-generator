<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProfileController;
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

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});


Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('home');
    })->middleware('auth');

    /* history */
   Route::get('/history', [InvoiceController::class, 'history'])->name('invoices.history');
Route::get('/history/data', [InvoiceController::class, 'getHistoryData'])->name('invoices.history.data');

    Route::post('/save-invoice', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/invoices/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
    // Edit Invoice
    Route::get('/invoices/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
    // Update Invoice
    Route::put('/invoices/{id}', [InvoiceController::class, 'update'])->name('invoices.update');
    // Delete Invoice
    Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');

    // routes/web.php
    Route::get('/invoices/{invoice}/view', [InvoiceController::class, 'view'])->name('invoices.view');
    /* Profile Routes */
    Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
});