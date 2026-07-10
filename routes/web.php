<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookCopyController;
use App\Http\Controllers\FineController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;

Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::controller(BookController::class)->prefix('books')->name('books.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::get('/import', 'importForm')->name('import.form');
        Route::post('/import', 'import')->name('import');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::get('/{id}', 'show')->name('show');
        Route::match(['put', 'patch'], '/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    Route::controller(MemberController::class)->prefix('members')->name('members.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::get('/{id}', 'show')->name('show');
        Route::match(['put', 'patch'], '/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    Route::controller(InventoryController::class)->prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::redirect('/procurement', '/procurements')->name('procurement');
    });

    Route::controller(ProcurementController::class)->prefix('procurements')->name('procurements.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::post('/{id}/approve', 'approve')->name('approve');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    Route::controller(BookCopyController::class)->prefix('copies')->name('copies.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::match(['put', 'patch'], '/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    Route::controller(LoanController::class)->prefix('loans')->name('loans.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/borrow', 'borrow')->name('borrow');
        Route::post('/', 'store')->name('store');
        Route::get('/return', 'return')->name('return');
        Route::post('/{id}/return', 'processReturn')->name('processReturn');
        Route::get('/{id}', 'show')->name('show');
    });

    Route::controller(ReportController::class)->prefix('reports')->name('reports.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/circulation', 'circulation')->name('circulation');
        Route::get('/overdue', 'overdue')->name('overdue');
        Route::get('/collection', 'collection')->name('collection');
        Route::get('/members', 'members')->name('members');
        Route::get('/fines', 'fines')->name('fines');
    });

    Route::post('/fines/{fine}/paid', [FineController::class, 'markPaid'])->name('fines.paid');

    Route::controller(SettingController::class)->prefix('settings')->name('settings.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/edit', 'edit')->name('edit');
        Route::post('/', 'update')->name('update');
    });
});

Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
