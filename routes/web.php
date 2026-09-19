<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookCopyController;
use App\Http\Controllers\BookTypeController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\SchoolYearController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : Inertia::render('Auth/Login');
});

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/admin', fn () => redirect()->route('dashboard'))
        ->middleware('role:admin')
        ->name('admin.dashboard');

    Route::middleware('role:admin')->group(function (): void {
        Route::get('anggota/import', [MemberController::class, 'import'])->name('members.import');
        Route::post('anggota/import/preview', [MemberController::class, 'preview'])->name('members.import.preview');
        Route::post('anggota/import/confirm', [MemberController::class, 'confirmImport'])->name('members.import.confirm');
        Route::get('anggota/import/template', [MemberController::class, 'template'])->name('members.import.template');
        Route::get('anggota/create', [MemberController::class, 'create'])->name('members.create');
        Route::resource('anggota', MemberController::class)->except('create', 'edit')->names('members')->parameters(['anggota' => 'member']);
        Route::get('anggota/{member}/edit', [MemberController::class, 'edit'])->name('members.edit');

        Route::get('buku/import', [BookController::class, 'import'])->name('books.import');
        Route::post('buku/import/preview', [BookController::class, 'previewImport'])->name('books.import.preview');
        Route::post('buku/import/confirm', [BookController::class, 'confirmImport'])->name('books.import.confirm');
        Route::get('buku/import/template', [BookController::class, 'template'])->name('books.import.template');
        Route::get('buku/create', [BookController::class, 'create'])->name('books.create');
        Route::resource('buku', BookController::class)->except('create', 'edit')->names('books')->parameters(['buku' => 'book']);
        Route::get('buku/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
        Route::get('buku/{book}/eksemplar/create', [BookCopyController::class, 'create'])->name('book-copies.create');
        Route::post('buku/{book}/eksemplar', [BookCopyController::class, 'store'])->name('book-copies.store');
        Route::get('eksemplar/{book_copy}/edit', [BookCopyController::class, 'edit'])->name('book-copies.edit');
        Route::patch('eksemplar/{book_copy}', [BookCopyController::class, 'update'])->name('book-copies.update');
        Route::delete('eksemplar/{book_copy}', [BookCopyController::class, 'destroy'])->name('book-copies.destroy');
        Route::get('inventaris/create', [InventoryController::class, 'create'])->name('inventories.create');
        Route::resource('inventaris', InventoryController::class)->except('create', 'edit')->names('inventories')->parameters(['inventaris' => 'inventory']);
        Route::get('inventaris/{inventory}/edit', [InventoryController::class, 'edit'])->name('inventories.edit');

        Route::get('school-years/create', [SchoolYearController::class, 'create'])->name('school-years.create');
        Route::resource('school-years', SchoolYearController::class)->except('create', 'edit')->names('school-years');
        Route::get('school-years/{school_year}/edit', [SchoolYearController::class, 'edit'])->name('school-years.edit');

        Route::get('classrooms/create', [ClassroomController::class, 'create'])->name('classrooms.create');
        Route::resource('classrooms', ClassroomController::class)->except('create', 'edit')->names('classrooms');
        Route::get('classrooms/{classroom}/edit', [ClassroomController::class, 'edit'])->name('classrooms.edit');

        Route::get('book-types/create', [BookTypeController::class, 'create'])->name('book-types.create');
        Route::resource('book-types', BookTypeController::class)->except('create', 'edit')->names('book-types');
        Route::get('book-types/{book_type}/edit', [BookTypeController::class, 'edit'])->name('book-types.edit');

        Route::get('publishers/create', [PublisherController::class, 'create'])->name('publishers.create');
        Route::resource('publishers', PublisherController::class)->except('create', 'edit')->names('publishers');
        Route::get('publishers/{publisher}/edit', [PublisherController::class, 'edit'])->name('publishers.edit');

        Route::get('authors/create', [AuthorController::class, 'create'])->name('authors.create');
        Route::resource('authors', AuthorController::class)->except('create', 'edit')->names('authors');
        Route::get('authors/{author}/edit', [AuthorController::class, 'edit'])->name('authors.edit');
    });
});
