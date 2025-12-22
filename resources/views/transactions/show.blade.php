@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>
            <i class="fa-solid fa-receipt"></i> Detail Transaksi #{{ $transaction->code ?? $transaction->id }}
        </h3>
        <a href="{{ route('riwayat.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Informasi Transaksi -->
    <div class="card shadow-sm mb-3 border-0">
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-4"><strong>Tanggal:</strong> {{ $transaction->created_at->format('d-m-Y') }}</div>
                <div class="col-md-4"><strong>Waktu:</strong> {{ $transaction->created_at->format('H:i') }}</div>
                <div class="col-md-4"><strong>Order Type:</strong> {{ ucfirst($transaction->order_type ?? '---') }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4"><strong>Customer:</strong> {{ $transaction->customer_name ?? '-' }}</div>
                <div class="col-md-4"><strong>Metode Bayar:</strong> {{ ucfirst($transaction->payment_method ?? '-') }}</div>
                <div class="col-md-4">
                    <strong>Status:</strong>
                    @if ($transaction->status === 'cancel')
                        <span class="badge bg-danger">Dibatalkan</span>
                    @elseif ($transaction->status === 'paid')
                        <span class="badge bg-success">Lunas</span>
                    @elseif ($transaction->status === 'pending')
                        <span class="badge bg-warning">Pending</span>
                    @else
                        <span class="badge bg-secondary">{{ ucfirst($transaction->status) }}</span>
                    @endif
                </div>
            </div>

            {{-- Alasan pembatalan --}}
            @if ($transaction->status === 'cancel' && !empty($transaction->cancel_reason))
                <div class="mt-3 p-3 bg-light border rounded">
                    <div><strong>Alasan Pembatalan:</strong> {{ $transaction->cancel_reason }}</div>
                    <div><strong>Dibatalkan pada:</strong> {{ $transaction->cancelled_at ? $transaction->cancelled_at->format('d-m-Y H:i') : '-' }}</div>
                </div>
            @endif
        </div>
    </div>

    <!-- Tombol Batal Transaksi -->
    @if($transaction->status !== 'cancel')
    <div class="mb-3">
        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
            <i class="fa-solid fa-xmark"></i> Batalkan Transaksi
        </button>
    </div>
    @endif

    <!-- Daftar Item -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-light fw-bold">Daftar Item</div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th>Diskon</th>
                        <th>Catatan</th>
                        <th class="text-end">Harga</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($transaction->items as $item)
                    @php
                        $hargaAwal = $item->price;
                        $hargaSetelahDiskonItem = $hargaAwal;
                        $diskonItemText = '';

                        if ($item->discount > 0) {
                            if ($item->discount_type === 'percent') {
                                $hargaSetelahDiskonItem -= $hargaAwal * ($item->discount / 100);
                                $diskonItemText = $item->discount . '%';
                            } else {
                                $hargaSetelahDiskonItem -= $item->discount;
                                $diskonItemText = 'Rp ' . number_format($item->discount, 0, ',', '.');
                            }
                        }

                        // Global Discount
                        $hargaSetelahDiskonGlobal = $hargaSetelahDiskonItem;
                        $diskonGlobalText = '';
                        if ($transaction->discount_value > 0) {
                            if ($transaction->discount_type === 'percent') {
                                $hargaSetelahDiskonGlobal -= $hargaSetelahDiskonGlobal * ($transaction->discount_value / 100);
                                $diskonGlobalText = $transaction->discount_value . '%';
                            } else {
                                $proporsi = $item->subtotal / $transaction->items->sum(fn($i) => $i->subtotal);
                                $hargaSetelahDiskonGlobal -= ($transaction->discount_value * $proporsi / $item->quantity);
                                $diskonGlobalText = 'Rp ' . number_format($transaction->discount_value, 0, ',', '.');
                            }
                        }

                        $subtotalSetelahDiskon = $hargaSetelahDiskonGlobal * $item->quantity;
                    @endphp

                    <tr @if($transaction->status === 'cancel') class="table-danger" @endif>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>
                            @if ($diskonItemText) Diskon Item: {{ $diskonItemText }} @endif
                            @if ($diskonGlobalText) <br>Diskon Global: {{ $diskonGlobalText }} @endif
                            @if (!$diskonItemText && !$diskonGlobalText) - @endif
                        </td>
                        <td>{{ $item->note ?? '-' }}</td>
                        <td class="text-end">
                            @if ($diskonItemText || $diskonGlobalText)
                                <div class="text-muted text-decoration-line-through small">
                                    Rp{{ number_format($hargaAwal, 0, ',', '.') }}
                                </div>
                                <div class="fw-semibold text-success">
                                    Rp{{ number_format($hargaSetelahDiskonGlobal, 0, ',', '.') }}
                                </div>
                            @else
                                Rp{{ number_format($hargaAwal, 0, ',', '.') }}
                            @endif
                        </td>
                        <td class="text-end fw-semibold">
                            Rp{{ number_format($subtotalSetelahDiskon, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
                </tbody>

                <!-- Ringkasan Diskon Global -->
                @if ($transaction->discount_value > 0)
                <tr>
                    <td colspan="5" class="text-end fw-bold text-danger">Diskon Global</td>
                    <td class="text-end text-danger">
                        - @if ($transaction->discount_type === 'percent')
                            {{ $transaction->discount_value }}%
                        @else
                            Rp{{ number_format($transaction->discount_value, 0, ',', '.') }}
                        @endif
                    </td>
                </tr>
                @endif

                <!-- Total -->
                <tr>
                    <td colspan="5" class="text-end fw-bold">Total</td>
                    <td class="text-end fw-bold">
                        Rp{{ number_format($transaction->total, 0, ',', '.') }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<!-- Modal Pembatalan -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form action="{{ route('transaksi.cancel', $transaction->id) }}" method="POST" class="modal-content">
        @csrf
        <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="cancelModalLabel">
                <i class="fa-solid fa-triangle-exclamation me-2"></i> Batalkan Transaksi
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <p>Pilih alasan pembatalan transaksi ini:</p>
            <select name="reason" class="form-select" required>
                <option value="">-- Pilih Alasan --</option>
                <option value="Kesalahan input">Kesalahan input</option>
                <option value="Pelanggan membatalkan">Pelanggan membatalkan</option>
                <option value="Stok habis">Stok habis</option>
                <option value="Lainnya">Lainnya</option>
            </select>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-sm btn-danger">Batalkan</button>
        </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl)
    })
});
</script>
@endpush
