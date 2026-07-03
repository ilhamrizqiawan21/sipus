<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AuthController;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Books
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
Route::get('/books/import', [BookController::class, 'importForm'])->name('books.import.form');
Route::post('/books/import', [BookController::class, 'import'])->name('books.import');
Route::post('/books', [BookController::class, 'store'])->name('books.store');
Route::get('/books/{id}/edit', [BookController::class, 'edit'])->name('books.edit');
Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');
Route::match(['put','patch'],'/books/{id}', [BookController::class, 'update'])->name('books.update');
Route::delete('/books/{id}', [BookController::class, 'destroy'])->name('books.destroy');

// Members
Route::get('/members', [MemberController::class, 'index'])->name('members.index');
Route::get('/members/{id}', [MemberController::class, 'show'])->name('members.show');

// Inventory
Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
Route::get('/inventory/procurement', [InventoryController::class, 'procurement'])->name('inventory.procurement');
// Procurements
Route::get('/procurements', [App\Http\Controllers\ProcurementController::class, 'index'])->name('procurements.index');
Route::get('/procurements/create', [App\Http\Controllers\ProcurementController::class, 'create'])->name('procurements.create');
Route::post('/procurements', [App\Http\Controllers\ProcurementController::class, 'store'])->name('procurements.store');
Route::get('/procurements/{id}', [App\Http\Controllers\ProcurementController::class, 'show'])->name('procurements.show');
Route::post('/procurements/{id}/approve', [App\Http\Controllers\ProcurementController::class, 'approve'])->name('procurements.approve');
Route::delete('/procurements/{id}', [App\Http\Controllers\ProcurementController::class, 'destroy'])->name('procurements.destroy');
// Book copies (eksamplar)
Route::get('/copies', [App\Http\Controllers\BookCopyController::class, 'index'])->name('copies.index');
Route::get('/copies/create', [App\Http\Controllers\BookCopyController::class, 'create'])->name('copies.create');
Route::post('/copies', [App\Http\Controllers\BookCopyController::class, 'store'])->name('copies.store');
Route::get('/copies/{id}/edit', [App\Http\Controllers\BookCopyController::class, 'edit'])->name('copies.edit');
Route::match(['put','patch'],'/copies/{id}', [App\Http\Controllers\BookCopyController::class, 'update'])->name('copies.update');
Route::delete('/copies/{id}', [App\Http\Controllers\BookCopyController::class, 'destroy'])->name('copies.destroy');

// Loans
Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
Route::get('/loans/borrow', [LoanController::class, 'borrow'])->name('loans.borrow');
Route::get('/loans/return', [LoanController::class, 'return'])->name('loans.return');

// Reports
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

// Settings
Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');

// Auth (web)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');