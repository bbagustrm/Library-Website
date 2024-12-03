<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ProfileController;

use App\Http\Middleware\AdminMiddleware;


Route::get('/', [AuthController::class, 'index'])->name('auth.index');
Route::post('/auth/verify', [AuthController::class, 'verify'])->name('auth.verify');
Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::get('/register', [AuthController::class, 'registerForm'])->name('auth.registerForm');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');



Route::get('/user/home', [UserController::class, 'index'])->name('user.home')->middleware('isUser');
Route::get('/books/{id}', [UserController::class, 'show'])->name('books.show')->middleware('isUser');;
Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store')->middleware('isUser');
Route::post('/wishlist', [WishlistController::class, 'store'])->name('wishlist.store')->middleware('isUser');
Route::get('/user/profile', [UserController::class, 'profile'])->name('user.profile')->middleware('isUser');

Route::get('/staff/dashboard', [StaffController::class, 'index'])->name('staff.dashboard')->middleware('isStaff');
Route::post('/staff/peminjaman', [StaffController::class, 'storePeminjaman'])->name('staff.peminjaman')->middleware('isStaff');
Route::post('/staff/pengembalian', [StaffController::class, 'storePengembalian'])->name('staff.pengembalian')->middleware('isStaff');
Route::get('/staff/search-buku/{codeBuku}', [StaffController::class, 'searchBuku'])->middleware('isStaff');
Route::get('/staff/profile', [StaffController::class, 'profile'])->name('staff.profile')->middleware('isStaff');
Route::get('/staff/table', [StaffController::class, 'table'])->name('staff.table')->middleware('isStaff');
Route::get('/staff/detail/{id}', [StaffController::class, 'detail'])->name('staff.detail')->middleware('isStaff');;
Route::post('/staff/update-status/{id}', [StaffController::class, 'updateStatus'])->name('staff.updateStatus')->middleware('isStaff');


Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard')->middleware('isAdmin');
Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile')->middleware('isAdmin');
Route::get('/admin/table', [AdminController::class, 'table'])->name('admin.table')->middleware('isAdmin');
Route::get('/admin/detail/{id}', [AdminController::class, 'detail'])->name('admin.detail')->middleware('isAdmin');
Route::post('/admin/update-status/{id}', [AdminController::class, 'updateStatus'])->name('admin.updateStatus')->middleware('isAdmin');
Route::get('/admin/books', [AdminController::class, 'dataBooks'])->name('admin.dataBooks')->middleware('isAdmin');
Route::get('/admin/books/create', [AdminController::class, 'createBook'])->name('admin.createBook')->middleware('isAdmin');
Route::get('/admin/books/edit/{id}', [AdminController::class, 'editBook'])->name('admin.editBook')->middleware('isAdmin');
Route::post('/books/store', [AdminController::class, 'storeBook'])->name('admin.storeBook')->middleware('isAdmin');
Route::put('/books/update/{id}', [AdminController::class, 'updateBook'])->name('admin.updateBook')->middleware('isAdmin');
Route::delete('/books/delete/{id}', [AdminController::class, 'deleteBook'])->name('admin.deleteBook')->middleware('isAdmin');

