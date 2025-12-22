<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\CashOpening;

class CheckCashOpening
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah ada kas awal di tanggal hari ini
        $today = Carbon::today();
        $opening = CashOpening::whereRaw('DATE(`date`) = ?', [$today->toDateString()])->first();

        if (!$opening) {
            // Kalau belum ada, redirect ke halaman kas awal
            return redirect()->route('kas.opening.form')
                ->with('error', 'Silakan masukkan kas awal terlebih dahulu sebelum melakukan transaksi.');
        }

        // Jika sudah ada kas awal, lanjut request berikutnya
        return $next($request);
    }
}
