@extends('layouts.app')

@section('title', 'Daftar Karyawan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0"><i class="fa-solid fa-users me-2 text-primary"></i> Daftar Karyawan</h3>
    @if(auth()->user()->role === 'owner')
        <a href="{{ route('karyawan.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> Tambah Karyawan
        </a>
    @endif
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-circle-exclamation me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body">
        @if($users->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="fw-semibold">#</th>
                        <th class="fw-semibold">Nama</th>
                        <th class="fw-semibold">Email</th>
                        <th class="fw-semibold" style="width:200px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                    <i class="fa-solid fa-user text-primary"></i>
                                </div>
                                <span class="fw-semibold">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if(auth()->user()->role === 'owner')
                                <a href="{{ route('karyawan.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                                </a>
                                <form action="{{ route('karyawan.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus karyawan ini?')">
                                        <i class="fa-solid fa-trash me-1"></i> Hapus
                                    </button>
                                </form>
                            @else
                                <span class="text-muted small">Tidak ada aksi</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $users->links() }}
        </div>
        @else
            <div class="text-center py-5">
                <i class="fa-solid fa-users-slash fs-1 text-muted mb-3"></i>
                <p class="text-muted">Belum ada data karyawan</p>
                @if(auth()->user()->role === 'owner')
                    <a href="{{ route('karyawan.create') }}" class="btn btn-primary mt-2">
                        <i class="fa-solid fa-plus me-1"></i> Tambah Karyawan Pertama
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
