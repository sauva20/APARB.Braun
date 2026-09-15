<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Apar;
use App\Models\User;
use App\Mail\AparExpiryReminder;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

#[Signature('app:send-apar-expiry-notification')]
#[Description('Sends H-30 expiration reminder for APAR to EHSS and PIC')]
class SendAparExpiryNotification extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $targetDate = Carbon::today()->addDays(30);
        $formattedDate = $targetDate->locale('id')->translatedFormat('d F Y');
        
        $apars = Apar::whereDate('tgl_kedaluwarsa', $targetDate)->with(['lokasi.gedung', 'jenis'])->get();
        
        if ($apars->isEmpty()) {
            $this->info('No APAR expiring in 30 days.');
            return;
        }

        $ehssUsers = User::where('role', 'EHSS')->get();
        $ehssEmails = $ehssUsers->pluck('email')->toArray();

        // Send one master email to all EHSS users
        if (!empty($ehssEmails)) {
            Mail::to($ehssEmails)->send(new AparExpiryReminder($apars, $formattedDate, 'Tim EHSS'));
            $this->info('Sent master expiration reminder to EHSS users.');
        }

        // Send to PIC (Staff) based on Gedung assignments
        $staffUsers = User::where('role', 'Staff')->with('gedungs')->get();

        foreach ($staffUsers as $staff) {
            $assignedGedungIds = $staff->gedungs->pluck('id')->toArray();
            
            if (empty($assignedGedungIds)) {
                continue;
            }

            // Filter APARs that belong to this staff's assigned gedungs
            $staffApars = $apars->filter(function ($apar) use ($assignedGedungIds) {
                if ($apar->lokasi && $apar->lokasi->gedung_id) {
                    return in_array($apar->lokasi->gedung_id, $assignedGedungIds);
                }
                return false;
            });

            if ($staffApars->isNotEmpty() && !in_array($staff->email, $ehssEmails)) {
                Mail::to($staff->email)->send(new AparExpiryReminder($staffApars, $formattedDate, $staff->name));
                $this->info("Sent expiration reminder to PIC: {$staff->email} for " . $staffApars->count() . " APARs.");
            }
        }
    }
}
