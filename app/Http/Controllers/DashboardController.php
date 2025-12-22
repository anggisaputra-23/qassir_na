<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\CashOpening;
use App\Models\CashClosing;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // ==============================
        // FILTER TANGGAL (default: hari ini)
        // ==============================
        $tanggalAwal = $request->input('tanggal_awal')
            ? Carbon::parse($request->input('tanggal_awal'))->startOfDay()
            : Carbon::today()->startOfDay();

        $tanggalAkhir = $request->input('tanggal_akhir')
            ? Carbon::parse($request->input('tanggal_akhir'))->endOfDay()
            : Carbon::today()->endOfDay();

        // ==============================
        // NOMINAL TUNAI DAN NON TUNAI HARI INI
        // ==============================
        $transaksiHariIni = Transaction::whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
            ->where('status', 'paid');

        $pendapatanTunai = (clone $transaksiHariIni)->where('payment_method', 'cash')->sum('total');
        $pendapatanNonTunai = (clone $transaksiHariIni)->where('payment_method', '!=', 'cash')->sum('total');

        // ==============================
        // HARI INI & KEMARIN TOTAL
        // ==============================
        $pendapatanHariIniTotal = $pendapatanTunai + $pendapatanNonTunai;

        $pendapatanKemarin = Transaction::whereBetween('created_at', [
                $tanggalAwal->copy()->subDay(),
                $tanggalAkhir->copy()->subDay()
            ])
            ->where('status', 'paid')
            ->sum('total');

        $percentHari = $this->hitungPersentase($pendapatanHariIniTotal, $pendapatanKemarin);
        $statusHari = $this->statusPerbandingan($pendapatanHariIniTotal, $pendapatanKemarin);

        // ==============================
        // BULAN INI & BULAN LALU
        // ==============================
        $bulanIniAwal = Carbon::now()->startOfMonth();
        $bulanIniAkhir = Carbon::now()->endOfMonth();

        $bulanLaluAwal = Carbon::now()->subMonth()->startOfMonth();
        $bulanLaluAkhir = Carbon::now()->subMonth()->endOfMonth();

        $pendapatanBulanIni = Transaction::whereBetween('created_at', [$bulanIniAwal, $bulanIniAkhir])
            ->where('status', 'paid')
            ->sum('total');

        $pendapatanBulanLalu = Transaction::whereBetween('created_at', [$bulanLaluAwal, $bulanLaluAkhir])
            ->where('status', 'paid')
            ->sum('total');

        $percentBulan = $this->hitungPersentase($pendapatanBulanIni, $pendapatanBulanLalu);
        $statusBulan = $this->statusPerbandingan($pendapatanBulanIni, $pendapatanBulanLalu);

        // ==============================
        // TAHUN INI & TAHUN LALU
        // ==============================
        $tahunIniAwal = Carbon::now()->startOfYear();
        $tahunIniAkhir = Carbon::now()->endOfYear();

        $tahunLaluAwal = Carbon::now()->subYear()->startOfYear();
        $tahunLaluAkhir = Carbon::now()->subYear()->endOfYear();

        $pendapatanTahunIni = Transaction::whereBetween('created_at', [$tahunIniAwal, $tahunIniAkhir])
            ->where('status', 'paid')
            ->sum('total');

        $pendapatanTahunLalu = Transaction::whereBetween('created_at', [$tahunLaluAwal, $tahunLaluAkhir])
            ->where('status', 'paid')
            ->sum('total');

        $percentTahun = $this->hitungPersentase($pendapatanTahunIni, $pendapatanTahunLalu);
        $statusTahun = $this->statusPerbandingan($pendapatanTahunIni, $pendapatanTahunLalu);

        // ==============================
        // RINGKASAN TRANSAKSI
        // ==============================
        $totalTransaksiHariIni = $transaksiHariIni->count();

        $totalPenjualanHariIni = TransactionItem::whereHas('transaction', function ($q) use ($tanggalAwal, $tanggalAkhir) {
                $q->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
                  ->where('status', 'paid');
            })
            ->sum('quantity');

        $totalTransaksi = Transaction::count();

        // ==============================
        // PRODUK TERLARIS (TOP 5)
        // ==============================
        $produkTerlaris = TransactionItem::select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->whereHas('transaction', function ($q) use ($tanggalAwal, $tanggalAkhir) {
                $q->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
                  ->where('status', 'paid');
            })
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->with('product')
            ->take(5)
            ->get();

        // ==============================
        // GRAFIK PENDAPATAN
        // ==============================
        $pendapatanRange = Transaction::select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('SUM(total) as total')
            )
            ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
            ->where('status', 'paid')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('tanggal', 'asc')
            ->get();

        $labelsPendapatan = $pendapatanRange->pluck('tanggal')->map(function ($d) {
            return Carbon::parse($d)->format('d M');
        });
        $dataPendapatan = $pendapatanRange->pluck('total');

        // ==============================
        // LABEL PRODUK
        // ==============================
        $labelsProduk = $produkTerlaris->pluck('product.name');
        $dataProduk = $produkTerlaris->pluck('total_qty');

        // ==============================
        // CEK KAS AWAL HARI INI
        // ==============================
        $today = Carbon::today();
        $opening = CashOpening::whereDate('date', $today)->first();
        $showOpeningModal = !$opening; // true kalau belum ada kas awal hari ini

        // ==============================
        // SHIFT AKTIF (BELUM DITUTUP)
        // ==============================
        $activeShift = CashOpening::with('user')
            ->whereDoesntHave('cashClosing') // shift yang belum ditutup
            ->latest('date')
            ->first();

        // ==============================
        // RETURN VIEW
        // ==============================
        return view('dashboard', compact(
            'tanggalAwal',
            'tanggalAkhir',
            'pendapatanTunai',
            'pendapatanNonTunai',
            'pendapatanHariIniTotal',
            'pendapatanKemarin',
            'pendapatanBulanIni',
            'pendapatanBulanLalu',
            'pendapatanTahunIni',
            'pendapatanTahunLalu',
            'percentHari',
            'percentBulan',
            'percentTahun',
            'statusHari',
            'statusBulan',
            'statusTahun',
            'totalTransaksiHariIni',
            'totalPenjualanHariIni',
            'totalTransaksi',
            'produkTerlaris',
            'labelsPendapatan',
            'dataPendapatan',
            'labelsProduk',
            'dataProduk',
            'showOpeningModal',
            'activeShift' // kirim ke view
        ));
    }

    private function hitungPersentase($sekarang, $sebelumnya)
    {
        if ($sebelumnya == 0) {
            return $sekarang > 0 ? 100 : 0;
        }
        return (($sekarang - $sebelumnya) / $sebelumnya) * 100;
    }

    private function statusPerbandingan($sekarang, $sebelumnya)
    {
        if ($sekarang > $sebelumnya) {
            return 'up';
        } elseif ($sekarang < $sebelumnya) {
            return 'down';
        }
        return 'equal';
    }
}
