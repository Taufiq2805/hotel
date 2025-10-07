<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\TipeKamarController;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ResepsionisDashboardController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\ReportSewaController;
use App\Http\Controllers\HousekeepingDashboardController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\RiwayatPengeluaranController;
use App\Http\Controllers\LaporanController;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    return redirect()->route($role . '.dashboard');
})->middleware('auth')->name('dashboard');

Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => ['auth', IsAdmin::class]
], function() {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('tipekamar', TipeKamarController::class);
    Route::resource('kamar', KamarController::class);
    Route::resource('user', UserController::class);
    Route::get('/riwayat-sewa', [ReportSewaController::class, 'index'])->name('riwayat.index');
    Route::get('/pemasukan', [PemasukanController::class, 'index'])->name('pemasukan.index');
    Route::get('/pengeluaran', [RiwayatPengeluaranController::class, 'index'])->name('pengeluaran.index');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export/pdf', [LaporanController::class, 'exportPDF'])->name('laporan.export.pdf');
    Route::get('/laporan/export/excel', [LaporanController::class, 'exportExcel'])->name('laporan.export.excel');
});

Route::group([
    'prefix'     => 'resepsionis',
    'as'         => 'resepsionis.',
    'middleware' => ['auth'],
], function () {
    Route::get('/', [ResepsionisDashboardController::class, 'index'])->name('dashboard');
    Route::resource('reservasi', ReservasiController::class);
    Route::post('/reservasi/{id}/selesai', [ReservasiController::class, 'selesai'])->name('reservasi.selesai');
}); 

Route::group([
    'prefix'     => 'housekeeping',
    'as'         => 'housekeeping.',
    'middleware' => ['auth'],
], function () {
    Route::get('/', [HousekeepingDashboardController::class, 'index'])->name('dashboard');
    
    // Resource route
    Route::resource('maintenance', MaintenanceController::class);
    Route::resource('pengeluaran', PengeluaranController::class);
    
    // Tambahan route untuk update status
    Route::post('maintenance/status/{id}', [MaintenanceController::class, 'updateStatus'])
        ->name('maintenance.updateStatus');
});

