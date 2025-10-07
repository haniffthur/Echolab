<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccessController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\AccessLogController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/tap', [AccessController::class, 'handleTap']);
Route::get('/dashboard-data', [DashboardController::class, 'getData']);
Route::get('/dashboard-data', [DashboardController::class, 'getData'])->name('api.dashboard.data');
// Route untuk data real-time halaman Log Tap Kartu
Route::get('/access-logs-data', [AccessLogController::class, 'getApiData'])->name('api.access-logs.data');
