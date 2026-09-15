<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->with('gedungs')->latest()->paginate(10)->withQueryString();
        $roles = User::select('role')->whereNotNull('role')->distinct()->pluck('role');
        $gedungs = \App\Models\Gedung::orderBy('nama')->get();

        return view('users.index', compact('users', 'roles', 'gedungs'));
    }

    public function exportPdf(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->with('gedungs')->latest()->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('users.pdf', compact('users'))->setPaper('a4', 'portrait');
        return $pdf->stream('Data_User_' . date('Ymd_His') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->with('gedungs')->latest()->get();

        $filename = "Data_User_" . date('Ymd_His') . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['No', 'User ID', 'Nama', 'Email', 'Gedung', 'Role', 'Jadwal Inspeksi'];

        $callback = function() use($users, $columns) {
            $file = fopen('php://output', 'w');
            // add BOM to fix UTF-8 in Excel
            fputs($file, $bom =(chr(0xEF) . chr(0xBB) . chr(0xBF)));
            fputcsv($file, $columns);

            foreach ($users as $index => $user) {
                $gedungsStr = $user->gedungs->pluck('nama')->implode(', ');
                $row = [
                    $index + 1,
                    $user->employee_id ?? 'n/a',
                    $user->name,
                    $user->email,
                    $gedungsStr ?: 'n/a',
                    $user->role,
                    $user->jadwal_rutin_tanggal ? 'Tanggal ' . $user->jadwal_rutin_tanggal : 'n/a',
                ];
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|string|max:50|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|string|in:EHSS,Staff',
            'jadwal_rutin_tanggal' => 'nullable|integer|min:1|max:31',
            'gedungs' => 'nullable|array',
            'gedungs.*' => 'exists:gedung,id',
        ], [
            'employee_id.required' => 'User ID wajib diisi.',
            'employee_id.unique' => 'User ID ini sudah terdaftar pada akun lain. Silakan gunakan User ID yang berbeda.',
        ]);

        // Auto-generate unique 4-digit PIN
        do {
            $pin = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        } while (User::where('pin', $pin)->exists());

        // Default password for all users (will be changed on setup)
        $password = \Illuminate\Support\Str::random(16);

        $user = User::create([
            'employee_id' => $request->employee_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'role' => $request->role,
            'pin' => $pin,
            'jadwal_rutin_tanggal' => $request->role === 'Staff' ? $request->jadwal_rutin_tanggal : null,
        ]);

        if ($request->role === 'Staff' && $request->has('gedungs')) {
            $user->gedungs()->sync($request->gedungs);
        }

        // Create signed setup URL
        $setupUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'setup-password',
            now()->addDays(3),
            ['user' => $user->id]
        );

        // Send Email
        \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\NewUserRegistered($user, $setupUrl));

        return redirect()->back()->with('success', 'User berhasil ditambahkan! Email setup telah dikirim.');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'employee_id' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|string|in:EHSS,Staff',
            'pin' => ['nullable', 'string', 'size:4', 'regex:/^[0-9]+$/', Rule::unique('users')->ignore($user->id)],
            'jadwal_rutin_tanggal' => 'nullable|integer|min:1|max:31',
            'gedungs' => 'nullable|array',
            'gedungs.*' => 'exists:gedung,id',
        ], [
            'employee_id.required' => 'User ID wajib diisi.',
            'employee_id.unique' => 'User ID ini sudah terdaftar pada akun lain. Silakan gunakan User ID yang berbeda.',
        ]);

        $data = [
            'employee_id' => $request->employee_id,
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'jadwal_rutin_tanggal' => $request->role === 'Staff' ? $request->jadwal_rutin_tanggal : null,
        ];

        if ($request->filled('pin')) {
            $data['pin'] = $request->pin;
        }

        $user->update($data);

        if ($request->role === 'Staff') {
            $user->gedungs()->sync($request->gedungs ?? []);
        } else {
            $user->gedungs()->detach();
        }

        return redirect()->back()->with('success', 'Data user berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus!');
    }
}
