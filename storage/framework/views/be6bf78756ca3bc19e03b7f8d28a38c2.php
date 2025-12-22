<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-4">
    <h3 class="mb-4">
        <i class="fa-solid fa-clock"></i> Riwayat Transaksi
    </h3>

    
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" class="row row-cols-lg-auto g-3 align-items-end mb-3">
                
                <div class="col-12">
                    <label for="kasir" class="form-label mb-0">Nama Kasir:</label>
                    <input type="text" name="kasir" id="kasir"
                           class="form-control border-secondary"
                           placeholder="Cari nama kasir..."
                           value="<?php echo e(request('kasir')); ?>">
                </div>

                
                <div class="col-12">
                    <label for="tanggal_awal" class="form-label mb-0">Dari:</label>
                    <input type="date" name="tanggal_awal" id="tanggal_awal"
                           class="form-control border-secondary"
                           value="<?php echo e(request('tanggal_awal')); ?>">
                </div>
                <div class="col-12">
                    <label for="tanggal_akhir" class="form-label mb-0">Sampai:</label>
                    <input type="date" name="tanggal_akhir" id="tanggal_akhir"
                           class="form-control border-secondary"
                           value="<?php echo e(request('tanggal_akhir')); ?>">
                </div>

                
                <div class="col-12">
                    <label for="payment_method" class="form-label mb-0">Metode:</label>
                    <select name="payment_method" id="payment_method" class="form-select border-secondary">
                        <option value="">Semua</option>
                        <option value="cash" <?php echo e(request('payment_method') == 'cash' ? 'selected' : ''); ?>>Tunai</option>
                        <option value="non_tunai" <?php echo e(request('payment_method') == 'non_tunai' ? 'selected' : ''); ?>>Non Tunai</option>
                    </select>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-magnifying-glass"></i> Cari
                    </button>
                    <a href="<?php echo e(route('riwayat.index')); ?>" class="btn btn-outline-secondary ms-2">
                        <i class="fa-solid fa-rotate"></i> Reset
                    </a>
                </div>
            </form>

            
            <div class="d-flex gap-2 flex-wrap">
                <a href="<?php echo e(route('riwayat.index', ['quick' => 'today'])); ?>" class="btn btn-outline-primary btn-sm">
                    <i class="fa-solid fa-calendar-day"></i> Hari Ini
                </a>
                <a href="<?php echo e(route('riwayat.index', ['quick' => 'week'])); ?>" class="btn btn-outline-primary btn-sm">
                    <i class="fa-solid fa-calendar-week"></i> Minggu Ini
                </a>
                <a href="<?php echo e(route('riwayat.index', ['quick' => 'month'])); ?>" class="btn btn-outline-primary btn-sm">
                    <i class="fa-solid fa-calendar"></i> Bulan Ini
                </a>
            </div>
        </div>
    </div>

    
    <?php if($riwayatPerHari->isEmpty()): ?>
        <div class="alert alert-info shadow-sm text-center">
            <i class="fa-solid fa-circle-info"></i> Tidak ada transaksi yang ditemukan.
        </div>
    <?php endif; ?>

    
    <?php $__currentLoopData = $riwayatPerHari; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $riwayat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="mb-5">
            <h5 class="fw-bold mb-3">
                <i class="fa-solid fa-calendar-day me-2 text-primary"></i>
                <?php echo e($riwayat['tanggal']); ?>

            </h5>

            <div class="alert alert-success py-2 px-3 mb-3 rounded-3 shadow-sm">
                <strong>Total Omzet:</strong> Rp<?php echo e(number_format($riwayat['total_harian'], 0, ',', '.')); ?>

            </div>

            <?php $no = 1; ?>
            <?php $__currentLoopData = $riwayat['transaksis']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="card border-0 shadow-sm mb-3 <?php if($trx->status === 'cancelled'): ?> opacity-75 <?php endif; ?>">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <div>
                            <a href="<?php echo e(route('transaksi.show', $trx->id)); ?>" class="text-decoration-none text-dark fw-semibold">
                                <i class="fa-solid fa-receipt text-primary"></i>
                                Transaksi #<?php echo e($no); ?> — <?php echo e($trx->customer_name ?: ucfirst($trx->order_type)); ?>

                            </a>

                            
                            <span class="badge rounded-pill px-3 py-1 ms-2
                                <?php if($trx->status === 'paid'): ?> bg-success
                                <?php elseif($trx->status === 'cancelled'): ?> bg-danger
                                <?php else: ?> bg-warning text-dark <?php endif; ?>">
                                <i class="fa-solid
                                    <?php if($trx->status === 'paid'): ?> fa-circle-check
                                    <?php elseif($trx->status === 'cancelled'): ?> fa-circle-xmark
                                    <?php else: ?> fa-clock <?php endif; ?> me-1"></i>
                                <?php echo e(strtoupper($trx->status)); ?>

                            </span>

                            
                            <?php if($trx->payment_method === 'cash'): ?>
                                <span class="badge bg-primary ms-2">Tunai</span>
                            <?php else: ?>
                                <span class="badge bg-info text-dark ms-2">Non Tunai</span>
                            <?php endif; ?>

                            
                            <span class="badge bg-secondary ms-2">Kasir: <?php echo e($trx->user->name ?? 'Tidak diketahui'); ?></span>
                        </div>
                        <div class="text-muted small">
                            <i class="fa-regular fa-clock"></i> <?php echo e($trx->created_at->format('H:i')); ?>

                        </div>
                    </div>

                    
                    <div class="card-body">
                        <ul class="list-group list-group-flush mb-3">
                            <?php $__currentLoopData = $trx->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $hargaAwal = $item->price;
                                    $subtotalItem = $item->subtotal;
                                    $hargaDiskon = $hargaAwal;
                                    $diskonItemText = '';

                                    if ($item->discount > 0) {
                                        $hargaDiskon = $item->discount_type === 'percent'
                                            ? $hargaDiskon * (1 - $item->discount / 100)
                                            : $hargaDiskon - $item->discount;
                                        $diskonItemText = $item->discount_type === 'percent'
                                            ? $item->discount . '%'
                                            : 'Rp ' . number_format($item->discount,0,',','.');
                                    }

                                    $diskonGlobalText = '';
                                    if ($trx->discount_value > 0) {
                                        if ($trx->discount_type === 'percent') {
                                            $hargaDiskon *= (1 - $trx->discount_value / 100);
                                            $diskonGlobalText = $trx->discount_value . '%';
                                        } else {
                                            $proporsi = $subtotalItem / $trx->items->sum(fn($i) => $i->subtotal);
                                            $diskonNominalGlobal = $trx->discount_value * $proporsi / $item->quantity;
                                            $hargaDiskon -= $diskonNominalGlobal;
                                            $diskonGlobalText = 'Rp ' . number_format($diskonNominalGlobal,0,',','.');
                                        }
                                    }

                                    $subtotalSetelahDiskon = $hargaDiskon * $item->quantity;
                                ?>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-semibold"><?php echo e($item->product->name); ?></div>
                                        <small class="text-muted">
                                            <?php echo e($item->quantity); ?>x
                                            <?php if($item->discount > 0 || $trx->discount_value > 0): ?>
                                                <span class="text-decoration-line-through text-muted">
                                                    Rp<?php echo e(number_format($hargaAwal,0,',','.')); ?>

                                                </span>
                                                <span class="fw-semibold text-success">
                                                    Rp<?php echo e(number_format($hargaDiskon,0,',','.')); ?>

                                                </span>
                                                <i class="fa-solid fa-tag text-danger ms-1"
                                                   data-bs-toggle="tooltip"
                                                   title="<?php if($diskonItemText): ?> Diskon Item: <?php echo e($diskonItemText); ?>. <?php endif; ?>
                                                          <?php if($diskonGlobalText): ?> Diskon Global: <?php echo e($diskonGlobalText); ?>. <?php endif; ?>">
                                                </i>
                                            <?php else: ?>
                                                Rp<?php echo e(number_format($hargaAwal,0,',','.')); ?>

                                            <?php endif; ?>

                                            <?php if($item->note): ?>
                                                — Catatan: <?php echo e($item->note); ?>

                                            <?php endif; ?>
                                        </small>
                                    </div>
                                    <div class="fw-semibold">
                                        Rp<?php echo e(number_format($subtotalSetelahDiskon,0,',','.')); ?>

                                    </div>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>

                        <?php if($trx->discount_value > 0): ?>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-bold text-danger">
                                    <i class="fa-solid fa-tags"></i> Diskon Global
                                </span>
                                <span class="text-danger">
                                    - <?php if($trx->discount_type === 'percent'): ?> <?php echo e($trx->discount_value); ?>%
                                      <?php else: ?> Rp<?php echo e(number_format($trx->discount_value,0,',','.')); ?> <?php endif; ?>
                                </span>
                            </div>
                        <?php endif; ?>

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="small text-muted">
                                <strong>Total:</strong> Rp<?php echo e(number_format($trx->total,0,',','.')); ?>

                            </div>
                            <a href="<?php echo e(route('transaksi.show',$trx->id)); ?>" class="btn btn-outline-primary btn-sm">
                                <i class="fa-regular fa-eye"></i> Detail
                            </a>
                        </div>
                    </div>
                </div>
                <?php $no++; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el);
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projek_pos\qassir_na\resources\views\transactions\history.blade.php ENDPATH**/ ?>