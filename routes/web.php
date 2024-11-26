<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PeminjamanController;

use App\Http\Middleware\AdminMiddleware;


Route::get('/', [AuthController::class, 'index'])->name('auth.index');
Route::post('/auth/verify', [AuthController::class, 'verify'])->name('auth.verify');
Route::get('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::get('/register', [AuthController::class, 'registerForm'])->name('auth.registerForm');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');


Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard')->middleware('isAdmin');
Route::get('/staff/dashboard', [StaffController::class, 'index'])->name('staff.dashboard')->middleware('isStaff');
Route::get('/user/home', [UserController::class, 'index'])->name('user.home')->middleware('isUser');
