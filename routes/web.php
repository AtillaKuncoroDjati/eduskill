<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\OneTimePasswordController;

use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ProfileController;

use App\Http\Controllers\Admin\KursusController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// [HOME] | Tampilan untuk halaman utama
Route::get('/', function () {
    return to_route('auth.view');
})->name('home.index');

// [AUTH] | Grup route untuk authentikasi
Route::prefix('auth')->group(function () {
    /* Route untuk halaman authentikasi */
    Route::get('login', [AuthController::class, 'login_view'])->name('auth.view');
    Route::post('login', [AuthController::class, 'auth_login'])->name('auth.login');
    Route::post('logout', [AuthController::class, 'auth_logout'])->name('auth.logout');

    /* Route untuk halaman registrasi */
    Route::get('register', [AuthController::class, 'register_view'])->name('auth.register.view');
    Route::post('register', [AuthController::class, 'auth_register'])->name('auth.register');

    /* Route untuk halaman lupa kata sandi */
    Route::get('password-reset', [AuthController::class, 'password_reset_view'])->name('password.email');
    Route::post('password-reset', [AuthController::class, 'password_reset'])->name('password.reset');
    Route::get('password-reset/{token}', [AuthController::class, 'password_update_view'])->name('password.update.view');
    Route::post('password-reset/{token}', [AuthController::class, 'password_update'])->name('password.update');

    /* Route untuk halaman verifikasi kode OTP */
    Route::get('verifikasi', [OneTimePasswordController::class, 'otp_view'])->name('otp.view');
    Route::post('verifikasi', [OneTimePasswordController::class, 'otp_verification'])->name('otp.verification');
    Route::post('verifikasi/resend', [OneTimePasswordController::class, 'otp_resend'])->name('otp.resend');
});

// [USER] | Grup route untuk pengguna yang sudah terautentikasi
Route::middleware(['auth', 'check.access'])->group(function () {
    /* Tampilan untuk halaman dashboard pengguna */
    Route::get('dashboard', [DashboardController::class, 'index'])->name('user.dashboard');

    /* Route untuk membersihkan cache aplikasi */
    Route::get('clear-cache', function () {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');

        session()->flash('success_message', 'Seluruh cache aplikasi, termasuk konfigurasi, route, dan tampilan berhasil dibersihkan untuk memastikan sistem berjalan dengan optimal.');
        return redirect()->back();
    })->name('clear.cache');

    /* Route untuk mengatur tampilan profil pengguna */
    Route::prefix('profile')->group(function () {
        Route::get('{user}', [ProfileController::class, 'show'])->name('user.profile');
        Route::post('{user}/update-avatar', [ProfileController::class, 'update_avatar'])->name('user.profile.update_avatar');
        Route::post('{user}/update-profile', [ProfileController::class, 'update_profile'])->name('user.profile.update_profile');
        Route::post('{user}/update-password', [ProfileController::class, 'update_password'])->name('user.profile.update_password');

        Route::post('{user}/update-otp-setting', [ProfileController::class, 'update_otp_setting'])->name('user.profile.update_otp_setting');
        Route::post('{user}/update-email-setting', [ProfileController::class, 'update_email_setting'])->name('user.profile.update_email_setting');
        Route::post('{user}/update-whatsapp-setting', [ProfileController::class, 'update_whatsapp_setting'])->name('user.profile.update_whatsapp_setting');
    });

    // [ADMIN] | Halaman untuk mengatur kursus (hanya untuk admin)
    Route::prefix('admin/kursus')->middleware('access:admin')->group(function () {
        Route::get('/', [KursusController::class, 'index'])->name('admin.kursus.index');
        Route::get('create', [KursusController::class, 'create'])->name('admin.kursus.create');
        Route::get('{kursus}/edit', [KursusController::class, 'edit'])->name('admin.kursus.edit');
        Route::post('store', [KursusController::class, 'store'])->name('admin.kursus.store');
        Route::post('{kursus}/update', [KursusController::class, 'update'])->name('admin.kursus.update');
        Route::post('request/data', [KursusController::class, 'request'])->name('admin.kursus.request');
        Route::post('delete', [KursusController::class, 'delete'])->name('admin.kursus.delete');
    });

    // [ADMIN] | Halaman untuk penggunaan (hanya untuk admin)
    // Route::prefix('admin/kursus')->middleware('access:admin')->group(function () {
    //     Route::get('/', [KursusController::class, 'index'])->name('admin.kursus.index');
    // });
});
