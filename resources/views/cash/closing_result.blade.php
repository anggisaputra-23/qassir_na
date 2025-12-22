@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body text-center">
            <h4 class="mb-3"><i class="fa-solid fa-circle-check text-success"></i> Tutup Kasir Berhasil</h4>

            <p><strong>Tanggal & Waktu:</strong>
               {{ \Carbon\Carbon::parse($closing->date)->translatedFormat('d F Y H:i') }}
            </p>

            <p><strong>Kas Awal:</strong> Rp {{ number_format($closing->opening_amount,0,',','.') }}</p>
            <p><strong>Total Omset:</strong> Rp {{ number_format($closing->total_sales,0,',','.') }}</p>
            <p><strong>Omset Tunai:</strong> Rp {{ number_format($closing->total_cash,0,',','.') }}</p>
            <p><strong>Omset Non Tunai:</strong> Rp {{ number_format($closing->total_non_cash,0,',','.') }}</p>
            <p><strong>Total Pengeluaran:</strong> Rp {{ number_format($closing->total_expenses,0,',','.') }}</p>
            <p><strong>Seharusnya Tunai:</strong> Rp {{ number_format($closing->expected_cash,0,',','.') }}</p>
            <p><strong>Uang Fisik:</strong> Rp {{ number_format($closing->actual_cash,0,',','.') }}</p>

            <p>
                <strong>Selisih:</strong>
                <span class="{{ $closing->difference < 0 ? 'text-danger' : ($closing->difference > 0 ? 'text-success' : 'text-muted') }}">
                    {{ $closing->difference >= 0 ? '+' : '-' }}
                    Rp {{ number_format(abs($closing->difference),0,',','.') }}
                </span>
            </p>

            <div class="mt-4 d-flex justify-content-center gap-2">
                <a href="{{ route('kas.print', $closing->id) }}" class="btn btn-primary">
                    <i class="fa-solid fa-print"></i> Cetak Struk
                </a>
                <a href="{{ route('kas.history') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-clock-rotate-left"></i> Lihat Riwayat Closing
                </a>
                <a href="{{ route('dashboard.index') }}" class="btn btn-outline-dark">
                    <i class="fa-solid fa-house"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
