<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$apars = \App\Models\Apar::orderBy('id')->get();
$seq = 1;
foreach ($apars as $apar) {
    // Current kode: PFE-T-B1, PFE-R-A5, etc.
    // Replace the number part with $seq
    $newKode = preg_replace('/\d+$/', $seq, $apar->kode);
    $apar->update(['kode' => $newKode]);
    $seq++;
}
echo "All APARs renumbered sequentially up to " . ($seq - 1);
