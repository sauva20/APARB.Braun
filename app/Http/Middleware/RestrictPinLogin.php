<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RestrictPinLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session('is_pin_login')) {
            $aparId = session('pin_login_apar_id');
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($aparId) {
                return redirect()->route('inspeksi.sukses', ['apar' => $aparId, 'restricted' => 1])->with('error', __('Akses ditolak. Anda tidak diizinkan masuk ke sistem utama melalui sesi Inspeksi (PIN). Sesi Anda telah dibatalkan demi keamanan.'));
            }

            return redirect('/')->withErrors([
                'email' => __('Akses ditolak. Anda tidak diizinkan masuk ke sistem utama melalui sesi Inspeksi (PIN). Sesi Anda telah dibatalkan demi keamanan.'),
            ]);
        }

        return $next($request);
    }
}
