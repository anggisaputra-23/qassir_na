<?php $__env->startSection('content'); ?>
<div class="container mt-4">

    
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h3 class="mb-2 mb-md-0">
            <i class="fa-solid fa-clock-rotate-left me-2"></i> Riwayat Penutupan Kas
        </h3>
        <small class="text-muted">Total Data: <?php echo e($closings->total()); ?></small>
    </div>

    
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('kas.history')); ?>" class="row g-3 align-items-end">

                <div class="col-md-4">
                    <label for="kasir" class="form-label">Nama Kasir</label>
                    <input type="text" name="kasir" id="kasir"
                           value="<?php echo e(request('kasir')); ?>" class="form-control"
                           placeholder="Cari nama kasir...">
                </div>

                <div class="col-md-3">
                    <label for="tanggal_mulai" class="form-label">Dari Tanggal</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                           value="<?php echo e(request('tanggal_mulai')); ?>" class="form-control">
                </div>

                <div class="col-md-3">
                    <label for="tanggal_selesai" class="form-label">Sampai Tanggal</label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                           value="<?php echo e(request('tanggal_selesai')); ?>" class="form-control">
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> Filter
                    </button>

                    <a href="<?php echo e(route('kas.history')); ?>" class="btn btn-outline-secondary w-100">
                        <i class="fa-solid fa-rotate-left me-1"></i> Reset
                    </a>
                </div>

            </form>
        </div>
    </div>

    
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
                    <?php $__empty_1 = true; $__currentLoopData = $closings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $closing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="text-center">
                                <?php echo e(\Carbon\Carbon::parse($closing->date)->translatedFormat('d M Y, H:i')); ?>

                            </td>

                            <td><?php echo e($closing->user->name ?? 'Tidak diketahui'); ?></td>

                            <td class="text-end">
                                Rp <?php echo e(number_format($closing->total_sales, 0, ',', '.')); ?>

                            </td>
                            <td class="text-end">
                                Rp <?php echo e(number_format($closing->total_cash, 0, ',', '.')); ?>

                            </td>
                            <td class="text-end">
                                Rp <?php echo e(number_format($closing->total_non_cash, 0, ',', '.')); ?>

                            </td>

                            <td class="text-end">
                                <?php if($closing->difference > 0): ?>
                                    <span class="badge bg-success">
                                        + Rp <?php echo e(number_format($closing->difference, 0, ',', '.')); ?>

                                    </span>
                                <?php elseif($closing->difference < 0): ?>
                                    <span class="badge bg-danger">
                                        - Rp <?php echo e(number_format(abs($closing->difference), 0, ',', '.')); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Rp 0</span>
                                <?php endif; ?>
                            </td>

                            <td class="text-center">
                                <a href="<?php echo e(route('kas.show', $closing->id)); ?>"
                                   class="btn btn-sm btn-outline-info me-1"
                                   title="Lihat Detail">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                <a href="<?php echo e(route('kas.print', $closing->id)); ?>"
                                   class="btn btn-sm btn-primary"
                                   target="_blank" title="Cetak Struk">
                                    <i class="fa-solid fa-print"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fa-solid fa-circle-info me-1"></i>
                                Belum ada data penutupan kas
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            
            <?php if($closings->hasPages()): ?>
                <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">

                    <div class="text-muted small mb-2 mb-md-0">
                        Menampilkan <?php echo e($closings->firstItem()); ?>–<?php echo e($closings->lastItem()); ?>

                        dari <?php echo e($closings->total()); ?> data
                    </div>

                    <div>
                        <?php echo e($closings->onEachSide(1)->links('pagination::bootstrap-5')); ?>

                    </div>

                </div>
            <?php endif; ?>

        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projek_pos\qassir_na\resources\views/cash/history.blade.php ENDPATH**/ ?>