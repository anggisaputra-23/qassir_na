@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3><i class="fa-solid fa-door-open"></i> Buka Kasir</h3>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger mt-3">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <form action="{{ route('kas.opening.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="opening_amount" class="form-label fw-bold">Kas Awal (Rp)</label>
                    <input type="number" name="opening_amount" id="opening_amount" class="form-control" required min="0" placeholder="Masukkan jumlah uang kas awal">
                </div>

                <div class="alert alert-info p-2">
                    <i class="fa-solid fa-circle-info"></i>
                    Setiap shift baru wajib memasukkan kas awal, meskipun di hari yang sama.
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
