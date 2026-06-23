<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <h3 class="mb-4"><i class="fa-solid fa-eye"></i> Detail Penutupan Kas</h3>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Tanggal:</strong> <?php echo e(\Carbon\Carbon::parse($closing->date)->translatedFormat('d M Y H:i')); ?></p>
                    <p><strong>Kasir:</strong> <?php echo e($closing->user->name ?? 'Tidak diketahui'); ?></p>
                    <p><strong>Total Penjualan:</strong> Rp <?php echo e(number_format($closing->total_sales, 0, ',', '.')); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Total Tunai:</strong> Rp <?php echo e(number_format($closing->total_cash, 0, ',', '.')); ?></p>
                    <p><strong>Total Non Tunai:</strong> Rp <?php echo e(number_format($closing->total_non_cash, 0, ',', '.')); ?></p>
                    <p><strong>Total Pengeluaran:</strong> Rp <?php echo e(number_format($closing->total_expenses, 0, ',', '.')); ?></p>
                    <p><strong>Selisih:</strong>
                        <span class="<?php echo e($closing->difference < 0 ? 'text-danger' : 'text-success'); ?>">
                            Rp <?php echo e(number_format($closing->difference, 0, ',', '.')); ?>

                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold text-success mb-3"><i class="fa-solid fa-coins"></i> Uang Fisik (Tunai)</h5>

                    <?php
                        $totalTunaiTransaksi = $transactions->where('payment_method', 'cash')->sum('total');
                        $uangFisik = $totalTunaiTransaksi - $closing->total_expenses;
                    ?>

                    <ul class="list-unstyled mb-0">
                        <li>Total Tunai Transaksi:
                            <strong>Rp <?php echo e(number_format($totalTunaiTransaksi, 0, ',', '.')); ?></strong>
                        </li>
                        <li>Pengeluaran:
                            <strong>- Rp <?php echo e(number_format($closing->total_expenses, 0, ',', '.')); ?></strong>
                        </li>
                        <hr>
                        <li><strong>Total Uang Fisik Hari Ini:</strong>
                            <span class="<?php echo e($uangFisik < 0 ? 'text-danger' : 'text-success'); ?>">
                                Rp <?php echo e(number_format($uangFisik, 0, ',', '.')); ?>

                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold text-primary mb-3"><i class="fa-solid fa-credit-card"></i> Uang Non Tunai</h5>

                    <?php
                        $nonTunai = $transactions->where('payment_method', '!=', 'cash');
                        $totalNonTunaiTransaksi = $nonTunai->sum('total');
                    ?>

                    <ul class="list-unstyled mb-0">
                        <li>Total Non Tunai:
                            <strong>Rp <?php echo e(number_format($totalNonTunaiTransaksi, 0, ',', '.')); ?></strong>
                        </li>
                        <li>Rincian Per Metode:
                            <ul>
                                <?php $__currentLoopData = $nonTunai->groupBy('payment_method'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method => $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e(strtoupper($method)); ?> — <?php echo e($trx->count()); ?> transaksi,
                                        Total: Rp <?php echo e(number_format($trx->sum('total'), 0, ',', '.')); ?>

                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </li>
                    </ul>

                </div>
            </div>
        </div>
    </div>

    
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="mb-3"><i class="fa-solid fa-wallet"></i> Daftar Pengeluaran</h5>

            <?php if($expenses->isEmpty()): ?>
                <p class="text-muted mb-0">Tidak ada pengeluaran.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="table-light text-center">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Jumlah (Rp)</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-center"><?php echo e($i+1); ?></td>
                                    <td><?php echo e($exp->expense_name); ?></td>
                                    <td class="text-end">Rp <?php echo e(number_format($exp->amount,0,',','.')); ?></td>
                                    <td class="text-center"><?php echo e(\Carbon\Carbon::parse($exp->date)->translatedFormat('d M Y H:i')); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>


    
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-3"><i class="fa-solid fa-receipt"></i> Ringkasan Transaksi</h5>

            <?php if($transactions->isEmpty()): ?>
                <p class="text-muted">Tidak ada transaksi.</p>
            <?php else: ?>
                <?php
                    $jumlahTransaksi = $transactions->count();
                    $totalNominal = $transactions->sum('total');
                    $totalTunai = $transactions->where('payment_method','cash')->sum('total');
                    $totalNonTunai = $transactions->where('payment_method','!=','cash')->sum('total');
                ?>

                <p><strong>Jumlah Transaksi:</strong> <?php echo e($jumlahTransaksi); ?></p>
                <p><strong>Total Nominal:</strong> Rp <?php echo e(number_format($totalNominal,0,',','.')); ?></p>

                <p class="mt-3 fw-bold">Rincian Menurut Metode Pembayaran:</p>
                <ul>
                    <li>Tunai — Rp <?php echo e(number_format($totalTunai, 0, ',', '.')); ?></li>
                    <li>Non Tunai — Rp <?php echo e(number_format($totalNonTunai, 0, ',', '.')); ?></li>
                </ul>
            <?php endif; ?>

            <div class="mt-4 d-flex gap-2">
                <a href="<?php echo e(route('kas.history')); ?>" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
                <a href="<?php echo e(route('kas.print', $closing->id)); ?>" class="btn btn-primary" target="_blank">
                    <i class="fa-solid fa-print"></i> Cetak Rekap
                </a>
            </div>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projek_pos\qassir_na\resources\views/cash/show.blade.php ENDPATH**/ ?>