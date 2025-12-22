@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h3 class="mb-0"><i class="fa-solid fa-chart-line me-2 text-primary"></i> Laporan Penjualan</h3>
</div>

{{-- Filter Periode --}}
<div class="card shadow-sm mb-4 border-0">
  <div class="card-header bg-white border-bottom">
    <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-calendar-days me-2"></i> Filter Periode</h6>
  </div>
  <div class="card-body">
    <form class="row g-3" method="GET" action="{{ route('laporan.penjualan') }}">
      <div class="col-md-3 col-sm-6">
        <label class="form-label fw-semibold">Mulai Bulan</label>
        <select name="start_month" class="form-select">
          @foreach($months as $m => $label)
            <option value="{{ $m }}" {{ $selected['start_month']==$m ? 'selected' : '' }}>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2 col-sm-6">
        <label class="form-label fw-semibold">Tahun</label>
        <select name="start_year" class="form-select">
          @foreach($years as $y)
            <option value="{{ $y }}" {{ $selected['start_year']==$y ? 'selected' : '' }}>{{ $y }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3 col-sm-6">
        <label class="form-label fw-semibold">Sampai Bulan</label>
        <select name="end_month" class="form-select">
          @foreach($months as $m => $label)
            <option value="{{ $m }}" {{ $selected['end_month']==$m ? 'selected' : '' }}>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2 col-sm-6">
        <label class="form-label fw-semibold">Tahun</label>
        <select name="end_year" class="form-select">
          @foreach($years as $y)
            <option value="{{ $y }}" {{ $selected['end_year']==$y ? 'selected' : '' }}>{{ $y }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2 col-sm-12 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">
          <i class="fa-solid fa-filter me-1"></i> Terapkan
        </button>
      </div>
    </form>
  </div>
</div>

{{-- Summary Cards --}}
<div class="row g-4 mb-4">
  <div class="col-lg-4 col-md-6">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div>
            <h6 class="text-muted mb-1">Omset Total</h6>
            <h3 class="fw-bold text-success mb-0">Rp {{ number_format($overallTotal,0,',','.') }}</h3>
          </div>
          <div class="bg-success bg-opacity-10 p-3 rounded-3">
            <i class="fa-solid fa-money-bill-wave text-success fs-4"></i>
          </div>
        </div>
        <div class="text-muted small">
          <i class="fa-regular fa-calendar me-1"></i>
          {{ $start->format('d M Y') }} - {{ $end->format('d M Y') }}
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4 col-md-6">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div>
            <h6 class="text-muted mb-1">Jumlah Kasir</h6>
            <h3 class="fw-bold text-primary mb-0">{{ count($rows) }}</h3>
          </div>
          <div class="bg-primary bg-opacity-10 p-3 rounded-3">
            <i class="fa-solid fa-users text-primary fs-4"></i>
          </div>
        </div>
        <div class="text-muted small">
          <i class="fa-solid fa-user-check me-1"></i>
          Kasir yang bertugas
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4 col-md-12">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div>
            <h6 class="text-muted mb-1">Rata-rata per Kasir</h6>
            <h3 class="fw-bold text-info mb-0">
              Rp {{ count($rows) > 0 ? number_format($overallTotal / count($rows), 0, ',', '.') : '0' }}
            </h3>
          </div>
          <div class="bg-info bg-opacity-10 p-3 rounded-3">
            <i class="fa-solid fa-chart-simple text-info fs-4"></i>
          </div>
        </div>
        <div class="text-muted small">
          <i class="fa-solid fa-calculator me-1"></i>
          Omset dibagi kasir
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Detail Perbandingan Kasir --}}
<div class="card shadow-sm border-0">
  <div class="card-header bg-white border-bottom">
    <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-ranking-star me-2"></i> Perbandingan Kinerja Kasir</h6>
  </div>
  <div class="card-body">
    @if(empty($rows))
      <div class="text-center py-5">
        <i class="fa-solid fa-inbox fs-1 text-muted mb-3"></i>
        <p class="text-muted">Tidak ada data transaksi pada periode ini</p>
      </div>
    @else
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th class="fw-semibold">Ranking</th>
              <th class="fw-semibold">Nama Kasir</th>
              <th class="fw-semibold">Jumlah Transaksi</th>
              <th class="fw-semibold">Total Omset</th>
              <th class="fw-semibold" style="width:30%">Kontribusi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($rows as $index => $r)
            <tr>
              <td>
                <span class="badge {{ $index === 0 ? 'bg-warning' : ($index === 1 ? 'bg-secondary' : 'bg-light text-dark') }}">
                  #{{ $index + 1 }}
                </span>
              </td>
              <td>
                <div class="d-flex align-items-center">
                  <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                    <i class="fa-solid fa-user text-primary"></i>
                  </div>
                  <span class="fw-semibold">{{ $r['name'] }}</span>
                </div>
              </td>
              <td>
                <span class="badge bg-info bg-opacity-10 text-info">
                  {{ $r['count'] }} transaksi
                </span>
              </td>
              <td class="fw-semibold text-success">Rp {{ number_format($r['total'],0,',','.') }}</td>
              <td>
                @php $pct = $overallTotal > 0 ? round($r['total'] / $overallTotal * 100) : 0; @endphp
                <div class="d-flex align-items-center gap-2">
                  <div class="progress flex-grow-1" style="height:20px">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $pct }}%" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100">
                      {{ $pct }}%
                    </div>
                  </div>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>
</div>
@endsection
