<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController; // Controller untuk login
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GateController; // <--- TAMBAHKAN INI
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\AccessLogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MonitorController;


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

// Halaman utama, langsung arahkan ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('monitor', [MonitorController::class, 'index'])->name('monitor.index');
// Grup untuk route yang hanya bisa diakses oleh tamu (belum login)
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});

// Grup untuk route yang wajib login (terproteksi)
Route::middleware('auth')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    Route::resource('gates', GateController::class); // <--- TAMBAHKAN BARIS INI
    Route::resource('employees', EmployeeController::class);
    Route::resource('cards', CardController::class);
    Route::get('access-logs', [AccessLogController::class, 'index'])->name('access-logs.index'); // <--- TAMBAHKAN BARIS INI
    Route::resource('users', UserController::class);
});
