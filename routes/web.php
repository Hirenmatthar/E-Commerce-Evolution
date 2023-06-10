<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ForgotPasswordController;
use Illuminate\Http\RedirectResponse;
use App\Models\Data;

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

Route::get('/admin/login', [AuthController::class, 'login']);
Route::get('/admin/index', [AuthController::class, 'index']);
Route::get('/admin/dashboard', [AuthController::class, 'dashboard']);
Route::get('/logout', [AuthController::class,'logout'])->name('logout');

Route::post('admin/login', [AuthController::class,'validateLoginForm'])->name('validateLoginForm');
Route::post('admin/register', [AuthController::class,'validateRegForm'])->name('validateRegForm');

Route::resource('/category', CategoryController::class);
Route::resource('/user', UserController::class);

Route::post('/admin/customer/fetch-state', [CustomerController::class, 'fetchState'])->name('getStatesByCountry');
Route::post('/admin/customer/fetch-city', [CustomerController::class, 'fetchCity'])->name('cities.getCitiesByState');
Route::get('/users-data', [CustomerController::class, 'getCustomers']);

Route::resource('/admin/customer', CustomerController::class);

Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');

Route::get('/admin/profile', [UserController::class, 'profile']);
Route::get('/admin/profile', [UserController::class, '_profile'])->name('auth.profile');
