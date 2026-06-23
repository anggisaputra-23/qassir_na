@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4"><i class="fa-solid fa-door-closed"></i> Tutup Kasir</h3>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('kas.closing.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Total Omset Hari Ini</label>
                    <input type="text" class="form-control"
                           value="Rp {{ number_format($totalSales,0,',','.') }}" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Total Tunai</label>
                    <input type="text" class="form-control"
                           value="Rp {{ number_format($totalCash,0,',','.') }}" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Total Non Tunai</label>
                    <input type="text" class="form-control"
                           value="Rp {{ number_format($totalNonCash,0,',','.') }}" readonly>
                </div>

                <div class="mb-2">
                    <label class="form-label">Pengeluaran Tambahan</label>

                    <div id="expenses-wrapper">
                        <div class="input-group mb-2">
                            <input type="text" name="expenses[0][expense_name]"
                                   class="form-control" placeholder="Nama Belanja">
                            <input type="number" name="expenses[0][amount]"
                                   class="form-control" placeholder="Nominal" min="0">
                        </div>
                    </div>

                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addExpenseRow()">+ Tambah Pengeluaran</button>
                </div>

                <div class="mb-3 mt-4">
                    <label class="form-label">Uang Fisik di Laci (Rp)</label>
                    <input type="number" name="actual_cash" class="form-control" required min="0" step="1" placeholder="Masukkan angka bulat tanpa desimal">
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-danger">
                        <i class="fa-solid fa-lock"></i> Simpan & Tutup Kasir
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
let expenseIndex = 1;
function addExpenseRow() {
    const wrapper = document.getElementById('expenses-wrapper');
    wrapper.insertAdjacentHTML('beforeend', `
        <div class="input-group mb-2">
            <input type="text" name="expenses[${expenseIndex}][expense_name]" class="form-control" placeholder="Nama Belanja">
            <input type="number" name="expenses[${expenseIndex}][amount]" class="form-control" placeholder="Nominal" min="0">
        </div>
    `);
    expenseIndex++;
}
</script>
@endsection
