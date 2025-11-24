<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckJamKerja
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && in_array($user->role, ['housekeeping', 'resepsionis'])) {
            $now = Carbon::now('Asia/Jakarta')->format('H:i:s');

            if ($now > $user->jam_selesai || $now < $user->jam_mulai) {
                Auth::logout();
                return redirect()->route('login')->withErrors([
                    'login' => 'Anda telah keluar karena di luar jam kerja.',
                ]);
            }
        }

        return $next($request);
    }
}
