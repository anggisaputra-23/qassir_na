<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h3 class="mb-0"><i class="fa-solid fa-chart-line me-2 text-primary"></i> Laporan Penjualan</h3>
</div>


<div class="card shadow-sm mb-4 border-0">
  <div class="card-header bg-white border-bottom">
    <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-calendar-days me-2"></i> Filter Periode</h6>
  </div>
  <div class="card-body">
    <form class="row g-3" method="GET" action="<?php echo e(route('laporan.penjualan')); ?>">
      <div class="col-md-3 col-sm-6">
        <label class="form-label fw-semibold">Mulai Bulan</label>
        <select name="start_month" class="form-select">
          <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($m); ?>" <?php echo e($selected['start_month']==$m ? 'selected' : ''); ?>><?php echo e($label); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
      <div class="col-md-2 col-sm-6">
        <label class="form-label fw-semibold">Tahun</label>
        <select name="start_year" class="form-select">
          <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($y); ?>" <?php echo e($selected['start_year']==$y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
      <div class="col-md-3 col-sm-6">
        <label class="form-label fw-semibold">Sampai Bulan</label>
        <select name="end_month" class="form-select">
          <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($m); ?>" <?php echo e($selected['end_month']==$m ? 'selected' : ''); ?>><?php echo e($label); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
      <div class="col-md-2 col-sm-6">
        <label class="form-label fw-semibold">Tahun</label>
        <select name="end_year" class="form-select">
          <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($y); ?>" <?php echo e($selected['end_year']==$y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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


<div class="row g-4 mb-4">
  <div class="col-lg-4 col-md-6">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div>
            <h6 class="text-muted mb-1">Omset Total</h6>
            <h3 class="fw-bold text-success mb-0">Rp <?php echo e(number_format($overallTotal,0,',','.')); ?></h3>
          </div>
          <div class="bg-success bg-opacity-10 p-3 rounded-3">
            <i class="fa-solid fa-money-bill-wave text-success fs-4"></i>
          </div>
        </div>
        <div class="text-muted small">
          <i class="fa-regular fa-calendar me-1"></i>
          <?php echo e($start->format('d M Y')); ?> - <?php echo e($end->format('d M Y')); ?>

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
            <h3 class="fw-bold text-primary mb-0"><?php echo e(count($rows)); ?></h3>
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
              Rp <?php echo e(count($rows) > 0 ? number_format($overallTotal / count($rows), 0, ',', '.') : '0'); ?>

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


<div class="card shadow-sm border-0">
  <div class="card-header bg-white border-bottom">
    <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-ranking-star me-2"></i> Perbandingan Kinerja Kasir</h6>
  </div>
  <div class="card-body">
    <?php if(empty($rows)): ?>
      <div class="text-center py-5">
        <i class="fa-solid fa-inbox fs-1 text-muted mb-3"></i>
        <p class="text-muted">Tidak ada data transaksi pada periode ini</p>
      </div>
    <?php else: ?>
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
            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td>
                <span class="badge <?php echo e($index === 0 ? 'bg-warning' : ($index === 1 ? 'bg-secondary' : 'bg-light text-dark')); ?>">
                  #<?php echo e($index + 1); ?>

                </span>
              </td>
              <td>
                <div class="d-flex align-items-center">
                  <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                    <i class="fa-solid fa-user text-primary"></i>
                  </div>
                  <span class="fw-semibold"><?php echo e($r['name']); ?></span>
                </div>
              </td>
              <td>
                <span class="badge bg-info bg-opacity-10 text-info">
                  <?php echo e($r['count']); ?> transaksi
                </span>
              </td>
              <td class="fw-semibold text-success">Rp <?php echo e(number_format($r['total'],0,',','.')); ?></td>
              <td>
                <?php $pct = $overallTotal > 0 ? round($r['total'] / $overallTotal * 100) : 0; ?>
                <div class="d-flex align-items-center gap-2">
                  <div class="progress flex-grow-1" style="height:20px">
                    <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo e($pct); ?>%" aria-valuenow="<?php echo e($pct); ?>" aria-valuemin="0" aria-valuemax="100">
                      <?php echo e($pct); ?>%
                    </div>
                  </div>
                </div>
              </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projek_pos\qassir_na\resources\views/reports/owner_sales.blade.php ENDPATH**/ ?>