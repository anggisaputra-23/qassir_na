<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\User;

class ReportController extends Controller
{
    // Owner-only: Laporan Penjualan (Omset) dalam rentang bulan/tahun
    public function ownerSales(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'owner') {
            abort(403, 'Hanya pemilik yang dapat mengakses laporan');
        }

        // Default rentang: bulan ini
        $startMonth = (int)($request->input('start_month', now()->month));
        $startYear  = (int)($request->input('start_year', now()->year));
        $endMonth   = (int)($request->input('end_month', now()->month));
        $endYear    = (int)($request->input('end_year', now()->year));

        $start = Carbon::create($startYear, $startMonth, 1)->startOfMonth();
        $end   = Carbon::create($endYear, $endMonth, 1)->endOfMonth();
        if ($end->lt($start)) {
            // Tukar jika rentang terbalik
            [$start, $end] = [$end->copy()->startOfMonth(), $start->copy()->endOfMonth()];
        }

        // Ambil transaksi dibayar dalam rentang
        $transactions = Transaction::where('status', 'paid')
            ->whereBetween('created_at', [$start, $end])
            ->get(['id','total','user_id','created_at']);

        $overallTotal = (float) $transactions->sum('total');

        // Agregasi per kasir
        $perCashier = $transactions
            ->groupBy('user_id')
            ->map(function($list){
                return [
                    'count' => $list->count(),
                    'total' => (float) $list->sum('total'),
                    'first_at' => optional($list->min('created_at')),
                    'last_at' => optional($list->max('created_at')),
                ];
            });

        // Ambil nama kasir
        $userIds = $perCashier->keys()->filter();
        $cashiers = User::whereIn('id', $userIds)->get(['id','name']);
        $namesById = $cashiers->keyBy('id')->map(fn($u)=> $u->name);

        // Siapkan data untuk view
        $rows = [];
        foreach ($perCashier as $uid => $agg) {
            $rows[] = [
                'user_id' => $uid,
                'name' => $namesById[$uid] ?? 'Tidak diketahui',
                'count' => $agg['count'],
                'total' => $agg['total'],
            ];
        }
        // Urutkan dari total terbesar
        usort($rows, fn($a,$b) => $b['total'] <=> $a['total']);

        // Data untuk pilihan bulan/tahun di form
        $months = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'Mei',6=>'Jun',7=>'Jul',8=>'Agu',9=>'Sep',10=>'Okt',11=>'Nov',12=>'Des'];
        $years = range(now()->year - 5, now()->year + 1);

        return view('reports.owner_sales', [
            'start' => $start,
            'end' => $end,
            'months' => $months,
            'years' => $years,
            'selected' => [
                'start_month' => $startMonth,
                'start_year' => $startYear,
                'end_month' => $endMonth,
                'end_year' => $endYear,
            ],
            'overallTotal' => $overallTotal,
            'rows' => $rows,
        ]);
    }
}
