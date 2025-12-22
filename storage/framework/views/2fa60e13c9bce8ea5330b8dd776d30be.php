<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body text-center">
            <h4 class="mb-3"><i class="fa-solid fa-circle-check text-success"></i> Tutup Kasir Berhasil</h4>

            <p><strong>Tanggal & Waktu:</strong>
               <?php echo e(\Carbon\Carbon::parse($closing->date)->translatedFormat('d F Y H:i')); ?>

            </p>

            <p><strong>Kas Awal:</strong> Rp <?php echo e(number_format($closing->opening_amount,0,',','.')); ?></p>
            <p><strong>Total Omset:</strong> Rp <?php echo e(number_format($closing->total_sales,0,',','.')); ?></p>
            <p><strong>Omset Tunai:</strong> Rp <?php echo e(number_format($closing->total_cash,0,',','.')); ?></p>
            <p><strong>Omset Non Tunai:</strong> Rp <?php echo e(number_format($closing->total_non_cash,0,',','.')); ?></p>
            <p><strong>Total Pengeluaran:</strong> Rp <?php echo e(number_format($closing->total_expenses,0,',','.')); ?></p>
            <p><strong>Seharusnya Tunai:</strong> Rp <?php echo e(number_format($closing->expected_cash,0,',','.')); ?></p>
            <p><strong>Uang Fisik:</strong> Rp <?php echo e(number_format($closing->actual_cash,0,',','.')); ?></p>

            <p>
                <strong>Selisih:</strong>
                <span class="<?php echo e($closing->difference < 0 ? 'text-danger' : ($closing->difference > 0 ? 'text-success' : 'text-muted')); ?>">
                    <?php echo e($closing->difference >= 0 ? '+' : '-'); ?>

                    Rp <?php echo e(number_format(abs($closing->difference),0,',','.')); ?>

                </span>
            </p>

            <div class="mt-4 d-flex justify-content-center gap-2">
                <a href="<?php echo e(route('kas.print', $closing->id)); ?>" class="btn btn-primary">
                    <i class="fa-solid fa-print"></i> Cetak Struk
                </a>
                <a href="<?php echo e(route('kas.history')); ?>" class="btn btn-secondary">
                    <i class="fa-solid fa-clock-rotate-left"></i> Lihat Riwayat Closing
                </a>
                <a href="<?php echo e(route('dashboard.index')); ?>" class="btn btn-outline-dark">
                    <i class="fa-solid fa-house"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projek_pos\qassir_na\resources\views\cash\closing_result.blade.php ENDPATH**/ ?>