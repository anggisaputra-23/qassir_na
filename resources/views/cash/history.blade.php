@extends('layouts.app')

@section('content')
<div class="container mt-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h3 class="mb-2 mb-md-0">
            <i class="fa-solid fa-clock-rotate-left me-2"></i> Riwayat Penutupan Kas
        </h3>
        <small class="text-muted">Total Data: {{ $closings->total() }}</small>
    </div>

    {{-- Filter Pencarian --}}
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <form method="GET" action="{{ route('kas.history') }}" class="row g-3 align-items-end">

                <div class="col-md-4">
                    <label for="kasir" class="form-label">Nama Kasir</label>
                    <input type="text" name="kasir" id="kasir"
                           value="{{ request('kasir') }}" class="form-control"
                           placeholder="Cari nama kasir...">
                </div>

                <div class="col-md-3">
                    <label for="tanggal_mulai" class="form-label">Dari Tanggal</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                           value="{{ request('tanggal_mulai') }}" class="form-control">
                </div>

                <div class="col-md-3">
                    <label for="tanggal_selesai" class="form-label">Sampai Tanggal</label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                           value="{{ request('tanggal_selesai') }}" class="form-control">
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> Filter
                    </button>

                    <a href="{{ route('kas.history') }}" class="btn btn-outline-secondary w-100">
                        <i class="fa-solid fa-rotate-left me-1"></i> Reset
                    </a>
                </div>

            </form>
        </div>
    </div>

    {{-- Tabel Riwayat --}}
    <div class="card shadow-sm border-0">
        <div class="card-body table-responsive">

            <table class="table table-hover table-striped align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th>Tanggal & Waktu</th>
                        <th>Kasir</th>
                        <th class="text-end">Total Omset</th>
                        <th class="text-end">Tunai</th>
                        <th class="text-end">Non Tunai</th>
                        <th class="text-end">Selisih</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($closings as $closing)
                        <tr>
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($closing->date)->translatedFormat('d M Y, H:i') }}
                            </td>

                            <td>{{ $closing->user->name ?? 'Tidak diketahui' }}</td>

                            <td class="text-end">
                                Rp {{ number_format($closing->total_sales, 0, ',', '.') }}
                            </td>
                            <td class="text-end">
                                Rp {{ number_format($closing->total_cash, 0, ',', '.') }}
                            </td>
                            <td class="text-end">
                                Rp {{ number_format($closing->total_non_cash, 0, ',', '.') }}
                            </td>

                            <td class="text-end">
                                @if($closing->difference > 0)
                                    <span class="badge bg-success">
                                        + Rp {{ number_format($closing->difference, 0, ',', '.') }}
                                    </span>
                                @elseif($closing->difference < 0)
                                    <span class="badge bg-danger">
                                        - Rp {{ number_format(abs($closing->difference), 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Rp 0</span>
                                @endif
                            </td>

                            <td class="text-center">
                                <a href="{{ route('kas.show', $closing->id) }}"
                                   class="btn btn-sm btn-outline-info me-1"
                                   title="Lihat Detail">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                <a href="{{ route('kas.print', $closing->id) }}"
                                   class="btn btn-sm btn-primary"
                                   target="_blank" title="Cetak Struk">
                                    <i class="fa-solid fa-print"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fa-solid fa-circle-info me-1"></i>
                                Belum ada data penutupan kas
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            @if ($closings->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">

                    <div class="text-muted small mb-2 mb-md-0">
                        Menampilkan {{ $closings->firstItem() }}–{{ $closings->lastItem() }}
                        dari {{ $closings->total() }} data
                    </div>

                    <div>
                        {{ $closings->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            @endif

        </div>
    </div>

</div>
@endsection
