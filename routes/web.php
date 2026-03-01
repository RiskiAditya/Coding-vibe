<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;

/**
 * Home Routes
 * 
 * Menangani routing untuk halaman utama dan welcome
 */
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::middleware('auth')->get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
Route::get('/buku', [HomeController::class, 'books'])->name('buku');
Route::middleware('auth')->get('/semua-buku', [HomeController::class, 'allBooksMember'])->name('all-books.member');
Route::middleware('auth')->get('/buku/{book}', [HomeController::class, 'showBook'])->name('books.show');
Route::middleware('auth')->get('/book-detail/{book}', [HomeController::class, 'showBook'])->name('book-detail');
Route::middleware('auth')->get('/baca/{book}', [HomeController::class, 'readBook'])->name('books.read');
Route::middleware('auth')->get('/riwayat-peminjaman', [HomeController::class, 'borrowingHistory'])->name('member.borrowing-history');
Route::get('/login', function() { return view('login'); })->name('login');
Route::get('/register', function() { return view('login'); })->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/**
 * Member Routes
 *
 * Protected routes for member functionality
 */
Route::middleware('auth')->group(function () {
    Route::post('/borrowing', [\App\Http\Controllers\BorrowingController::class, 'store'])->name('borrowing.store');
    Route::post('/borrowing/{borrowing}/return', [\App\Http\Controllers\BorrowingController::class, 'return'])->name('borrowing.return');
    
    // Fines management for members
    Route::get('/denda-saya', [\App\Http\Controllers\FineController::class, 'myFines'])->name('fines.my-fines');
    Route::post('/denda/{fine}/bayar', [\App\Http\Controllers\FineController::class, 'pay'])->name('fines.pay');
});

/**
 * Admin Routes
 *
 * Protected routes for admin functionality
 */
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // All books listing
    Route::get('semua-buku', [\App\Http\Controllers\HomeController::class, 'allBooksAdmin'])->name('all-books');

    // Books management
    Route::resource('books', \App\Http\Controllers\Admin\BookController::class);
    Route::post('books/{book}/toggle-featured', [\App\Http\Controllers\Admin\BookController::class, 'toggleFeatured'])->name('books.toggle-featured');

    // Categories management
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);

    // Members management
    Route::resource('members', \App\Http\Controllers\Admin\MemberController::class);

    // Borrowings management
    Route::resource('borrowings', \App\Http\Controllers\Admin\BorrowingController::class);
    Route::post('borrowings/{borrowing}/return', [\App\Http\Controllers\Admin\BorrowingController::class, 'adminReturn'])->name('borrowings.adminReturn');

    // Fines management
    Route::resource('fines', \App\Http\Controllers\Admin\FineController::class);
    Route::patch('fines/{fine}/mark-as-paid', [\App\Http\Controllers\Admin\FineController::class, 'markAsPaid'])->name('fines.markAsPaid');

    // Returns management
    Route::resource('returns', \App\Http\Controllers\Admin\ReturnController::class);

    // Reports
    Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
});
