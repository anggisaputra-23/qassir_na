@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <h3 class="mb-4">
        <i class="fa-solid fa-clock"></i> Riwayat Transaksi
    </h3>

    {{-- Filter Form --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" class="row row-cols-lg-auto g-3 align-items-end mb-3">
                {{-- Nama Kasir --}}
                <div class="col-12">
                    <label for="kasir" class="form-label mb-0">Nama Kasir:</label>
                    <input type="text" name="kasir" id="kasir"
                           class="form-control border-secondary"
                           placeholder="Cari nama kasir..."
                           value="{{ request('kasir') }}">
                </div>

                {{-- Rentang Tanggal --}}
                <div class="col-12">
                    <label for="tanggal_awal" class="form-label mb-0">Dari:</label>
                    <input type="date" name="tanggal_awal" id="tanggal_awal"
                           class="form-control border-secondary"
                           value="{{ request('tanggal_awal') }}">
                </div>
                <div class="col-12">
                    <label for="tanggal_akhir" class="form-label mb-0">Sampai:</label>
                    <input type="date" name="tanggal_akhir" id="tanggal_akhir"
                           class="form-control border-secondary"
                           value="{{ request('tanggal_akhir') }}">
                </div>

                {{-- Metode Pembayaran --}}
                <div class="col-12">
                    <label for="payment_method" class="form-label mb-0">Metode:</label>
                    <select name="payment_method" id="payment_method" class="form-select border-secondary">
                        <option value="">Semua</option>
                        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Tunai</option>
                        <option value="non_tunai" {{ request('payment_method') == 'non_tunai' ? 'selected' : '' }}>Non Tunai</option>
                    </select>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-magnifying-glass"></i> Cari
                    </button>
                    <a href="{{ route('riwayat.index') }}" class="btn btn-outline-secondary ms-2">
                        <i class="fa-solid fa-rotate"></i> Reset
                    </a>
                </div>
            </form>

            {{-- Quick Filter --}}
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('riwayat.index', ['quick' => 'today']) }}" class="btn btn-outline-primary btn-sm">
                    <i class="fa-solid fa-calendar-day"></i> Hari Ini
                </a>
                <a href="{{ route('riwayat.index', ['quick' => 'week']) }}" class="btn btn-outline-primary btn-sm">
                    <i class="fa-solid fa-calendar-week"></i> Minggu Ini
                </a>
                <a href="{{ route('riwayat.index', ['quick' => 'month']) }}" class="btn btn-outline-primary btn-sm">
                    <i class="fa-solid fa-calendar"></i> Bulan Ini
                </a>
            </div>
        </div>
    </div>

    {{-- Jika Tidak Ada Data --}}
    @if ($riwayatPerHari->isEmpty())
        <div class="alert alert-info shadow-sm text-center">
            <i class="fa-solid fa-circle-info"></i> Tidak ada transaksi yang ditemukan.
        </div>
    @endif

    {{-- Daftar Transaksi Per Hari --}}
    @foreach ($riwayatPerHari as $riwayat)
        <div class="mb-5">
            <h5 class="fw-bold mb-3">
                <i class="fa-solid fa-calendar-day me-2 text-primary"></i>
                {{ $riwayat['tanggal'] }}
            </h5>

            <div class="alert alert-success py-2 px-3 mb-3 rounded-3 shadow-sm">
                <strong>Total Omzet:</strong> Rp{{ number_format($riwayat['total_harian'], 0, ',', '.') }}
            </div>

            @php $no = 1; @endphp
            @foreach ($riwayat['transaksis'] as $trx)
                <div class="card border-0 shadow-sm mb-3 @if($trx->status === 'cancelled') opacity-75 @endif">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <div>
                            <a href="{{ route('transaksi.show', $trx->id) }}" class="text-decoration-none text-dark fw-semibold">
                                <i class="fa-solid fa-receipt text-primary"></i>
                                Transaksi #{{ $no }} — {{ $trx->customer_name ?: ucfirst($trx->order_type) }}
                            </a>

                            {{-- Status --}}
                            <span class="badge rounded-pill px-3 py-1 ms-2
                                @if($trx->status === 'paid') bg-success
                                @elseif($trx->status === 'cancelled') bg-danger
                                @else bg-warning text-dark @endif">
                                <i class="fa-solid
                                    @if($trx->status === 'paid') fa-circle-check
                                    @elseif($trx->status === 'cancelled') fa-circle-xmark
                                    @else fa-clock @endif me-1"></i>
                                {{ strtoupper($trx->status) }}
                            </span>

                            {{-- Metode Pembayaran --}}
                            @if($trx->payment_method === 'cash')
                                <span class="badge bg-primary ms-2">Tunai</span>
                            @else
                                <span class="badge bg-info text-dark ms-2">Non Tunai</span>
                            @endif

                            {{-- Nama Kasir --}}
                            <span class="badge bg-secondary ms-2">Kasir: {{ $trx->user->name ?? 'Tidak diketahui' }}</span>
                        </div>
                        <div class="text-muted small">
                            <i class="fa-regular fa-clock"></i> {{ $trx->created_at->format('H:i') }}
                        </div>
                    </div>

                    {{-- Item List --}}
                    <div class="card-body">
                        <ul class="list-group list-group-flush mb-3">
                            @foreach ($trx->items as $item)
                                @php
                                    $hargaAwal = $item->price;
                                    $subtotalItem = $item->subtotal;
                                    $hargaDiskon = $hargaAwal;
                                    $diskonItemText = '';

                                    if ($item->discount > 0) {
                                        $hargaDiskon = $item->discount_type === 'percent'
                                            ? $hargaDiskon * (1 - $item->discount / 100)
                                            : $hargaDiskon - $item->discount;
                                        $diskonItemText = $item->discount_type === 'percent'
                                            ? $item->discount . '%'
                                            : 'Rp ' . number_format($item->discount,0,',','.');
                                    }

                                    $diskonGlobalText = '';
                                    if ($trx->discount_value > 0) {
                                        if ($trx->discount_type === 'percent') {
                                            $hargaDiskon *= (1 - $trx->discount_value / 100);
                                            $diskonGlobalText = $trx->discount_value . '%';
                                        } else {
                                            $proporsi = $subtotalItem / $trx->items->sum(fn($i) => $i->subtotal);
                                            $diskonNominalGlobal = $trx->discount_value * $proporsi / $item->quantity;
                                            $hargaDiskon -= $diskonNominalGlobal;
                                            $diskonGlobalText = 'Rp ' . number_format($diskonNominalGlobal,0,',','.');
                                        }
                                    }

                                    $subtotalSetelahDiskon = $hargaDiskon * $item->quantity;
                                @endphp
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-semibold">{{ $item->product->name }}</div>
                                        <small class="text-muted">
                                            {{ $item->quantity }}x
                                            @if($item->discount > 0 || $trx->discount_value > 0)
                                                <span class="text-decoration-line-through text-muted">
                                                    Rp{{ number_format($hargaAwal,0,',','.') }}
                                                </span>
                                                <span class="fw-semibold text-success">
                                                    Rp{{ number_format($hargaDiskon,0,',','.') }}
                                                </span>
                                                <i class="fa-solid fa-tag text-danger ms-1"
                                                   data-bs-toggle="tooltip"
                                                   title="@if($diskonItemText) Diskon Item: {{ $diskonItemText }}. @endif
                                                          @if($diskonGlobalText) Diskon Global: {{ $diskonGlobalText }}. @endif">
                                                </i>
                                            @else
                                                Rp{{ number_format($hargaAwal,0,',','.') }}
                                            @endif

                                            @if($item->note)
                                                — Catatan: {{ $item->note }}
                                            @endif
                                        </small>
                                    </div>
                                    <div class="fw-semibold">
                                        Rp{{ number_format($subtotalSetelahDiskon,0,',','.') }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        @if ($trx->discount_value > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-bold text-danger">
                                    <i class="fa-solid fa-tags"></i> Diskon Global
                                </span>
                                <span class="text-danger">
                                    - @if($trx->discount_type === 'percent') {{ $trx->discount_value }}%
                                      @else Rp{{ number_format($trx->discount_value,0,',','.') }} @endif
                                </span>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="small text-muted">
                                <strong>Total:</strong> Rp{{ number_format($trx->total,0,',','.') }}
                            </div>
                            <a href="{{ route('transaksi.show',$trx->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fa-regular fa-eye"></i> Detail
                            </a>
                        </div>
                    </div>
                </div>
                @php $no++; @endphp
            @endforeach
        </div>
    @endforeach
</div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el);
    });
});
</script>
@endpush
@endsection
