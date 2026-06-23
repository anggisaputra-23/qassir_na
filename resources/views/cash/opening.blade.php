@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3><i class="fa-solid fa-door-open"></i> Buka Kasir</h3>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger mt-3">{{ session('error') }}</div>
    @elseif(session('warning'))
        <div class="alert alert-warning mt-3">{{ session('warning') }}</div>
    @endif

    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <form action="{{ route('kas.opening.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="opening_amount" class="form-label fw-bold">Kas Awal (Rp)</label>
                    <input type="number" name="opening_amount" id="opening_amount" class="form-control" required min="0"
                        placeholder="Masukkan jumlah uang kas awal (harus angka bulat tanpa desimal)"
                        value="">
                    <small class="text-muted">
                        <i class="fa-solid fa-circle-info me-1"></i>
                        Masukkan uang fisik yang ada di laci saat ini
                    </small>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="fa-solid fa-check"></i> Simpan & Buka Kasir
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
