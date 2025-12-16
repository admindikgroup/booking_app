<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\pages\HomePage;
use App\Http\Controllers\pages\Page2;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;

// Main Page Route
// Main Page Route
Route::get('/', [\App\Http\Controllers\BookingController::class, 'index'])->name('booking.index');
Route::get('/check-slots', [\App\Http\Controllers\BookingController::class, 'checkSlots'])->name('booking.check');
Route::post('/booking', [\App\Http\Controllers\BookingController::class, 'store'])->name('booking.store');

// Admin Auth Routes
Route::get('/admin/login', [\App\Http\Controllers\AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [\App\Http\Controllers\AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [\App\Http\Controllers\AdminAuthController::class, 'logout'])->name('admin.logout');
Route::post('/logout', [\App\Http\Controllers\AdminAuthController::class, 'logout'])->name('logout');
Route::get('/login', function () {
  return redirect()->route('admin.login');
})->name('login');

// Admin Routes (Protected)
Route::middleware(['auth'])->group(function () {
  Route::get('/admin', [\App\Http\Controllers\AdminController::class, 'index'])->name('admin.index');
  Route::put('/admin/booking/{id}', [\App\Http\Controllers\AdminController::class, 'updateStatus'])->name('admin.booking.update');
});

// Original Routes (keeping safe)
// Route::get('/', [HomePage::class, 'index'])->name('pages-home');
// Route::get('/', [HomePage::class, 'index'])->name('pages-home');
// Route::get('/page-2', [Page2::class, 'index'])->name('pages-page-2'); // Removed
Route::middleware(['auth'])->group(function () {
  Route::get('/admin/custom-booking', [\App\Http\Controllers\BookingSettingsController::class, 'index'])->name('booking-settings.index');
  Route::post('/admin/custom-booking', [\App\Http\Controllers\BookingSettingsController::class, 'update'])->name('booking-settings.update');
});

// locale
Route::get('/lang/{locale}', [LanguageController::class, 'swap']);
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');

// authentication
Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');
