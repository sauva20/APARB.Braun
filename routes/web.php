<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('index');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    });

    Route::get('/master-data', [\App\Http\Controllers\MasterDataController::class, 'index']);
    
    // Master Data Reference Routes
    Route::post('/master-data/gedung', [\App\Http\Controllers\MasterDataController::class, 'storeGedung']);
    Route::put('/master-data/gedung/{gedung}', [\App\Http\Controllers\MasterDataController::class, 'updateGedung']);
    Route::delete('/master-data/gedung/{gedung}', [\App\Http\Controllers\MasterDataController::class, 'destroyGedung']);
    
    Route::post('/master-data/lokasi', [\App\Http\Controllers\MasterDataController::class, 'storeLokasi']);
    Route::put('/master-data/lokasi/{lokasi}', [\App\Http\Controllers\MasterDataController::class, 'updateLokasi']);
    Route::delete('/master-data/lokasi/{lokasi}', [\App\Http\Controllers\MasterDataController::class, 'destroyLokasi']);
    
    Route::post('/master-data/jenis', [\App\Http\Controllers\MasterDataController::class, 'storeJenis']);
    Route::put('/master-data/jenis/{jenis_apar}', [\App\Http\Controllers\MasterDataController::class, 'updateJenis']);
    Route::delete('/master-data/jenis/{jenis_apar}', [\App\Http\Controllers\MasterDataController::class, 'destroyJenis']);
    
    Route::post('/master-data/kapasitas', [\App\Http\Controllers\MasterDataController::class, 'storeKapasitas']);
    Route::put('/master-data/kapasitas/{kapasitas_apar}', [\App\Http\Controllers\MasterDataController::class, 'updateKapasitas']);
    Route::delete('/master-data/kapasitas/{kapasitas_apar}', [\App\Http\Controllers\MasterDataController::class, 'destroyKapasitas']);

    // APAR Routes

    Route::post('/master-data/apar', [\App\Http\Controllers\MasterDataController::class, 'storeApar']);

    Route::put('/master-data/apar/{apar}', [\App\Http\Controllers\MasterDataController::class, 'updateApar']);
    Route::delete('/master-data/apar/{apar}', [\App\Http\Controllers\MasterDataController::class, 'destroyApar']);

    Route::get('/inspection-schedule', [\App\Http\Controllers\InspeksiController::class, 'index']);
    Route::post('/inspection-schedule', [\App\Http\Controllers\InspeksiController::class, 'storeJadwal']);
    Route::delete('/inspection-schedule/{jadwal}', [\App\Http\Controllers\InspeksiController::class, 'destroyJadwal']);

    Route::get('/inspeksi/mulai/{apar}', [\App\Http\Controllers\InspeksiController::class, 'create'])->name('inspeksi.mulai');
    Route::post('/inspeksi/store/{apar}', [\App\Http\Controllers\InspeksiController::class, 'store'])->name('inspeksi.store');
});
