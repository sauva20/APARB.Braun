<?php

namespace App\Console\Commands;

use App\Mail\InspectionReminder;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendInspectionReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim email pengingat inspeksi APAR (H-3 Rutin & H-1 Khusus)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai pengecekan jadwal inspeksi...');

        $today = now();
        $targetRutinDate = $today->copy()->addDays(3);
        $targetRutinDay = $targetRutinDate->day;

        // 1. Cek Jadwal Rutin (H-3)
        $usersRutin = User::where('role', 'Staff')
            ->where('jadwal_rutin_tanggal', $targetRutinDay)
            ->with('gedungs')
            ->get();

        foreach ($usersRutin as $user) {
            $this->info("Mengirim pengingat rutin ke: {$user->email}");
            Mail::to($user->email)->send(new InspectionReminder($user, 'Inspeksi Rutin Bulanan', $targetRutinDate->format('Y-m-d')));
        }

        $this->info('Selesai mengirim pengingat!');
    }
}
