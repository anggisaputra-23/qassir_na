@extends('layouts.app')

@section('title', 'Edit Karyawan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0"><i class="fa-solid fa-user-pen me-2 text-primary"></i> Edit Karyawan</h3>
    <a href="{{ route('karyawan.index') }}" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h6 class="alert-heading"><i class="fa-solid fa-circle-exclamation me-2"></i> Terdapat kesalahan:</h6>
        <ul class="mb-0">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('karyawan.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" placeholder="Masukkan nama lengkap" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" placeholder="contoh@email.com" required>
            </div>

            <hr class="my-4">
            <h6 class="text-muted mb-3">Ubah Password (Opsional)</h6>
            <p class="small text-muted">Kosongkan jika tidak ingin mengubah password</p>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label fw-semibold">Password Baru</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Minimal 6 karakter">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ulangi password">
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-save me-1"></i> Simpan Perubahan
                </button>
                <a href="{{ route('karyawan.index') }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-times me-1"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
