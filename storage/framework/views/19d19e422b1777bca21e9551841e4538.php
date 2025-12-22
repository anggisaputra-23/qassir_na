<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>
            <i class="fa-solid fa-receipt"></i> Detail Transaksi #<?php echo e($transaction->code ?? $transaction->id); ?>

        </h3>
        <a href="<?php echo e(route('riwayat.index')); ?>" class="btn btn-sm btn-outline-secondary">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Informasi Transaksi -->
    <div class="card shadow-sm mb-3 border-0">
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-4"><strong>Tanggal:</strong> <?php echo e($transaction->created_at->format('d-m-Y')); ?></div>
                <div class="col-md-4"><strong>Waktu:</strong> <?php echo e($transaction->created_at->format('H:i')); ?></div>
                <div class="col-md-4"><strong>Order Type:</strong> <?php echo e(ucfirst($transaction->order_type ?? '---')); ?></div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4"><strong>Customer:</strong> <?php echo e($transaction->customer_name ?? '-'); ?></div>
                <div class="col-md-4"><strong>Metode Bayar:</strong> <?php echo e(ucfirst($transaction->payment_method ?? '-')); ?></div>
                <div class="col-md-4">
                    <strong>Status:</strong>
                    <?php if($transaction->status === 'cancel'): ?>
                        <span class="badge bg-danger">Dibatalkan</span>
                    <?php elseif($transaction->status === 'paid'): ?>
                        <span class="badge bg-success">Lunas</span>
                    <?php elseif($transaction->status === 'pending'): ?>
                        <span class="badge bg-warning">Pending</span>
                    <?php else: ?>
                        <span class="badge bg-secondary"><?php echo e(ucfirst($transaction->status)); ?></span>
                    <?php endif; ?>
                </div>
            </div>

            
            <?php if($transaction->status === 'cancel' && !empty($transaction->cancel_reason)): ?>
                <div class="mt-3 p-3 bg-light border rounded">
                    <div><strong>Alasan Pembatalan:</strong> <?php echo e($transaction->cancel_reason); ?></div>
                    <div><strong>Dibatalkan pada:</strong> <?php echo e($transaction->cancelled_at ? $transaction->cancelled_at->format('d-m-Y H:i') : '-'); ?></div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Tombol Batal Transaksi -->
    <?php if($transaction->status !== 'cancel'): ?>
    <div class="mb-3">
        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
            <i class="fa-solid fa-xmark"></i> Batalkan Transaksi
        </button>
    </div>
    <?php endif; ?>

    <!-- Daftar Item -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-light fw-bold">Daftar Item</div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th>Diskon</th>
                        <th>Catatan</th>
                        <th class="text-end">Harga</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $transaction->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $hargaAwal = $item->price;
                        $hargaSetelahDiskonItem = $hargaAwal;
                        $diskonItemText = '';

                        if ($item->discount > 0) {
                            if ($item->discount_type === 'percent') {
                                $hargaSetelahDiskonItem -= $hargaAwal * ($item->discount / 100);
                                $diskonItemText = $item->discount . '%';
                            } else {
                                $hargaSetelahDiskonItem -= $item->discount;
                                $diskonItemText = 'Rp ' . number_format($item->discount, 0, ',', '.');
                            }
                        }

                        // Global Discount
                        $hargaSetelahDiskonGlobal = $hargaSetelahDiskonItem;
                        $diskonGlobalText = '';
                        if ($transaction->discount_value > 0) {
                            if ($transaction->discount_type === 'percent') {
                                $hargaSetelahDiskonGlobal -= $hargaSetelahDiskonGlobal * ($transaction->discount_value / 100);
                                $diskonGlobalText = $transaction->discount_value . '%';
                            } else {
                                $proporsi = $item->subtotal / $transaction->items->sum(fn($i) => $i->subtotal);
                                $hargaSetelahDiskonGlobal -= ($transaction->discount_value * $proporsi / $item->quantity);
                                $diskonGlobalText = 'Rp ' . number_format($transaction->discount_value, 0, ',', '.');
                            }
                        }

                        $subtotalSetelahDiskon = $hargaSetelahDiskonGlobal * $item->quantity;
                    ?>

                    <tr <?php if($transaction->status === 'cancel'): ?> class="table-danger" <?php endif; ?>>
                        <td><?php echo e($item->product->name); ?></td>
                        <td><?php echo e($item->quantity); ?></td>
                        <td>
                            <?php if($diskonItemText): ?> Diskon Item: <?php echo e($diskonItemText); ?> <?php endif; ?>
                            <?php if($diskonGlobalText): ?> <br>Diskon Global: <?php echo e($diskonGlobalText); ?> <?php endif; ?>
                            <?php if(!$diskonItemText && !$diskonGlobalText): ?> - <?php endif; ?>
                        </td>
                        <td><?php echo e($item->note ?? '-'); ?></td>
                        <td class="text-end">
                            <?php if($diskonItemText || $diskonGlobalText): ?>
                                <div class="text-muted text-decoration-line-through small">
                                    Rp<?php echo e(number_format($hargaAwal, 0, ',', '.')); ?>

                                </div>
                                <div class="fw-semibold text-success">
                                    Rp<?php echo e(number_format($hargaSetelahDiskonGlobal, 0, ',', '.')); ?>

                                </div>
                            <?php else: ?>
                                Rp<?php echo e(number_format($hargaAwal, 0, ',', '.')); ?>

                            <?php endif; ?>
                        </td>
                        <td class="text-end fw-semibold">
                            Rp<?php echo e(number_format($subtotalSetelahDiskon, 0, ',', '.')); ?>

                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>

                <!-- Ringkasan Diskon Global -->
                <?php if($transaction->discount_value > 0): ?>
                <tr>
                    <td colspan="5" class="text-end fw-bold text-danger">Diskon Global</td>
                    <td class="text-end text-danger">
                        - <?php if($transaction->discount_type === 'percent'): ?>
                            <?php echo e($transaction->discount_value); ?>%
                        <?php else: ?>
                            Rp<?php echo e(number_format($transaction->discount_value, 0, ',', '.')); ?>

                        <?php endif; ?>
                    </td>
                </tr>
                <?php endif; ?>

                <!-- Total -->
                <tr>
                    <td colspan="5" class="text-end fw-bold">Total</td>
                    <td class="text-end fw-bold">
                        Rp<?php echo e(number_format($transaction->total, 0, ',', '.')); ?>

                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<!-- Modal Pembatalan -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form action="<?php echo e(route('transaksi.cancel', $transaction->id)); ?>" method="POST" class="modal-content">
        <?php echo csrf_field(); ?>
        <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="cancelModalLabel">
                <i class="fa-solid fa-triangle-exclamation me-2"></i> Batalkan Transaksi
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <p>Pilih alasan pembatalan transaksi ini:</p>
            <select name="reason" class="form-select" required>
                <option value="">-- Pilih Alasan --</option>
                <option value="Kesalahan input">Kesalahan input</option>
                <option value="Pelanggan membatalkan">Pelanggan membatalkan</option>
                <option value="Stok habis">Stok habis</option>
                <option value="Lainnya">Lainnya</option>
            </select>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-sm btn-danger">Batalkan</button>
        </div>
    </form>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl)
    })
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projek_pos\qassir_na\resources\views\transactions\show.blade.php ENDPATH**/ ?>