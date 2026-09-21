<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InspeksiController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PasswordSetupController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/locale/{lang}', [App\Http\Controllers\LocaleController::class, 'setLocale'])->name('set-locale');
Route::get('/favicon.ico', function () {
    return response()->file(public_path('favicon.svg'), ['Content-Type' => 'image/svg+xml']);
});

Route::get('/', function () {
    return view('index');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/setup-password/{user}', [PasswordSetupController::class, 'show'])->name('setup-password')->middleware('signed');
Route::post('/setup-password/{user}', [PasswordSetupController::class, 'store'])->middleware('signed');
Route::get('/setup-password-success', [PasswordSetupController::class, 'success'])->name('setup-password.success');

use App\Http\Controllers\ForgotPasswordController;
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{user}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset')->middleware('signed');
Route::post('/reset-password/{user}', [ForgotPasswordController::class, 'reset'])->name('password.update')->middleware('signed');

Route::get('/display-report', [\App\Http\Controllers\DashboardController::class, 'displayReport'])->name('display-report');

Route::middleware(['auth', 'prevent-back-history'])->group(function () {
    Route::post('/profile/change-password', [ProfileController::class, 'updatePassword'])->name('profile.change-password');
    Route::post('/profile/change-pin', [ProfileController::class, 'updatePin'])->name('profile.change-pin');

    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index']);

    Route::get('/users/export-pdf', [UserController::class, 'exportPdf'])->name('users.export-pdf');
    Route::get('/users/export-excel', [UserController::class, 'exportExcel'])->name('users.export-excel');
    Route::get('/users/export-excel-preview', [UserController::class, 'previewExcel'])->name('users.export-excel-preview');
    Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);

    Route::get('/master-data', [MasterDataController::class, 'index'])->name('master-data.index');
    Route::get('/master-data/export-pdf', [MasterDataController::class, 'exportPdf'])->name('master-data.export-pdf');
    Route::get('/master-data/export-excel', [MasterDataController::class, 'exportExcel'])->name('master-data.export-excel');
    Route::get('/master-data/export-excel-preview', [MasterDataController::class, 'previewExcel'])->name('master-data.export-excel-preview');
    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
    Route::get('/activity-log/export-pdf', [ActivityLogController::class, 'exportPdf'])->name('activity-log.export-pdf');
    Route::get('/activity-log/export-excel', [ActivityLogController::class, 'exportExcel'])->name('activity-log.export-excel');
    Route::get('/activity-log/export-excel-preview', [ActivityLogController::class, 'previewExcel'])->name('activity-log.export-excel-preview');

    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-pdf', [\App\Http\Controllers\ReportController::class, 'exportPdf'])->name('reports.export-pdf');
    Route::get('/reports/export-excel', [\App\Http\Controllers\ReportController::class, 'exportExcel'])->name('reports.export-excel');
    Route::get('/reports/export-excel-preview', [\App\Http\Controllers\ReportController::class, 'previewExcel'])->name('reports.export-excel-preview');

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
    Route::get('/master-data/apar/{apar}/history', [MasterDataController::class, 'history']);

    Route::put('/master-data/apar/{apar}', [MasterDataController::class, 'updateApar']);
    Route::delete('/master-data/apar/{apar}', [MasterDataController::class, 'destroyApar']);

    Route::get('/inspection-schedule', [InspeksiController::class, 'index']);
    Route::get('/inspection-schedule/export-checklist', [InspeksiController::class, 'exportChecklist'])->name('inspection-schedule.export-checklist');
    Route::post('/inspection-schedule', [InspeksiController::class, 'storeJadwal']);
    Route::put('/inspection-schedule/{jadwal}', [InspeksiController::class, 'updateJadwal']);
    Route::delete('/inspection-schedule/{jadwal}', [InspeksiController::class, 'destroyJadwal']);

    Route::get('/inspeksi/pedoman/{apar}', [InspeksiController::class, 'pedoman'])->name('inspeksi.pedoman');
    Route::get('/inspeksi/mulai/{apar}', [InspeksiController::class, 'create'])->name('inspeksi.mulai');
    Route::get('/inspeksi/search', [InspeksiController::class, 'search'])->name('inspeksi.search');
    Route::get('/inspeksi/sukses/{apar}', [InspeksiController::class, 'sukses'])->name('inspeksi.sukses');
    Route::post('/inspeksi/store/{apar}', [InspeksiController::class, 'store'])->name('inspeksi.store');
});

Route::get('/scan/{kode}', [ScanController::class, 'index'])->name('scan.apar');
Route::post('/scan/{kode}/verify', [ScanController::class, 'verifyPin'])->name('scan.verify');

Route::get('/test-email/{type}', function ($type) {
    $user = \App\Models\User::with('gedungs')->first() ?? new \App\Models\User([
        'name' => 'Testing User',
        'email' => 'test@example.com',
        'role' => 'EHSS',
        'pin' => '123456',
        'jadwal_rutin_tanggal' => '15'
    ]);
    
    switch ($type) {
        case 'new-user':
            return new \App\Mail\NewUserRegistered($user, url('/setup-password/test'));
        case 'reset-password':
            return new \App\Mail\ResetPassword($user, url('/reset-password/test'));
        case 'inspection-assigned':
            $jadwals = \App\Models\JadwalInspeksi::with(['gedung.lokasi.apar', 'lokasi.apar'])->take(1)->get();
            if ($jadwals->isEmpty()) {
                // Mock a schedule if none exists
                $jadwals = collect([
                    (object) [
                        'tanggal_inspeksi' => now()->format('Y-m-d'),
                        'jenis_jadwal' => 'Inspeksi_Khusus',
                        'tipe_area' => 'gedung',
                        'catatan_tambahan' => 'Please check carefully.',
                        'gedung' => null,
                        'lokasi' => null
                    ]
                ]);
            }
            return new \App\Mail\InspectionAssigned($user, $jadwals, '123456');
        case 'apar-expiry':
            $apars = \App\Models\Apar::with(['lokasi.gedung', 'jenis'])->take(3)->get();
            return new \App\Mail\AparExpiryReminder($apars, now()->addDays(30)->format('Y-m-d'), $user->name);
        case 'inspection-reminder':
            return new \App\Mail\InspectionReminder($user, 'Inspeksi Rutin Bulanan', now()->addDays(3)->format('Y-m-d'));
        default:
            return 'Invalid type. Options: new-user, reset-password, inspection-assigned, apar-expiry, inspection-reminder';
    }
});
