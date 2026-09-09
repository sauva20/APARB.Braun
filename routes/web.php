<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InspeksiController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    });

    Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);

    Route::get('/master-data', [MasterDataController::class, 'index']);
    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');

    // Master Data Reference Routes
    Route::post('/master-data/gedung', [MasterDataController::class, 'storeGedung']);
    Route::put('/master-data/gedung/{gedung}', [MasterDataController::class, 'updateGedung']);
    Route::delete('/master-data/gedung/{gedung}', [MasterDataController::class, 'destroyGedung']);

    Route::post('/master-data/lokasi', [MasterDataController::class, 'storeLokasi']);
    Route::put('/master-data/lokasi/{lokasi}', [MasterDataController::class, 'updateLokasi']);
    Route::delete('/master-data/lokasi/{lokasi}', [MasterDataController::class, 'destroyLokasi']);

    Route::post('/master-data/jenis', [MasterDataController::class, 'storeJenis']);
    Route::put('/master-data/jenis/{jenis_apar}', [MasterDataController::class, 'updateJenis']);
    Route::delete('/master-data/jenis/{jenis_apar}', [MasterDataController::class, 'destroyJenis']);

    Route::post('/master-data/kapasitas', [MasterDataController::class, 'storeKapasitas']);
    Route::put('/master-data/kapasitas/{kapasitas_apar}', [MasterDataController::class, 'updateKapasitas']);
    Route::delete('/master-data/kapasitas/{kapasitas_apar}', [MasterDataController::class, 'destroyKapasitas']);

    // APAR Routes

    Route::post('/master-data/apar', [MasterDataController::class, 'storeApar']);

    Route::get('/master-data/apar/print-all-qr', [MasterDataController::class, 'printAllQr'])->name('apar.print-all-qr');
    Route::get('/master-data/apar/{apar}/qr-data', [MasterDataController::class, 'getQrData']);
    Route::get('/master-data/apar/{apar}/download-qr', [MasterDataController::class, 'downloadQr']);
    Route::get('/master-data/apar/{apar}/print-qr', [MasterDataController::class, 'printSingleQr'])->name('apar.print-single-qr');

    Route::put('/master-data/apar/{apar}', [MasterDataController::class, 'updateApar']);
    Route::delete('/master-data/apar/{apar}', [MasterDataController::class, 'destroyApar']);

    Route::get('/inspection-schedule', [InspeksiController::class, 'index']);
    Route::post('/inspection-schedule', [InspeksiController::class, 'storeJadwal']);
    Route::put('/inspection-schedule/{jadwal}', [InspeksiController::class, 'updateJadwal']);
    Route::delete('/inspection-schedule/{jadwal}', [InspeksiController::class, 'destroyJadwal']);

    Route::get('/inspeksi/mulai/{apar}', [InspeksiController::class, 'create'])->name('inspeksi.mulai');
    Route::post('/inspeksi/store/{apar}', [InspeksiController::class, 'store'])->name('inspeksi.store');
});

Route::get('/scan/{kode}', [ScanController::class, 'index'])->name('scan.apar');
Route::post('/scan/{kode}/verify', [ScanController::class, 'verifyPin'])->name('scan.verify');
