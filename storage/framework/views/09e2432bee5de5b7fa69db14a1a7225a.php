<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-4">

    
    <div class="mb-4">
        <h3 class="fw-bold mb-1">
            <i class="fa-solid fa-chart-line text-primary"></i> Dashboard
        </h3>
        <p class="text-muted mb-0">Ringkasan performa dan statistik penjualan Anda</p>
    </div>

    
    <div class="alert alert-<?php echo e(Auth::user()->role === 'owner' ? 'success' : 'primary'); ?> shadow-sm border-0 d-flex align-items-center alert-dismissible fade show">
        <div class="bg-white bg-opacity-25 rounded-circle p-2 me-3">
            <i class="fa-solid fa-user"></i>
        </div>
        <div class="flex-grow-1">
            Selamat datang, <strong><?php echo e(Auth::user()->name); ?></strong>!
            Anda masuk sebagai <strong><?php echo e(Auth::user()->role === 'owner' ? 'Owner' : 'Kasir'); ?></strong>.
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    
    <?php if(isset($activeShift)): ?>
    <div class="alert alert-info shadow-sm border-0 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center flex-grow-1">
            <div class="bg-white bg-opacity-25 rounded-circle p-2 me-3">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div>
                <div class="fw-semibold mb-1">Shift Aktif</div>
                <small>
                    Dibuka oleh <strong><?php echo e($activeShift->user->name ?? 'Kasir'); ?></strong>
                    pada <strong><?php echo e($activeShift->date->format('d/m/Y H:i')); ?></strong>
                    dengan kas awal <strong>Rp <?php echo e(number_format($activeShift->opening_amount, 0, ',', '.')); ?></strong>
                </small>
            </div>
        </div>
        <div>
            <a href="<?php echo e(route('kas.closing.form')); ?>" class="btn btn-sm btn-danger">
                <i class="fa-solid fa-lock me-1"></i> Tutup Kasir
            </a>
        </div>
    </div>
    <?php else: ?>
    <div class="alert alert-warning shadow-sm border-0 d-flex align-items-center">
        <div class="bg-white bg-opacity-25 rounded-circle p-2 me-3">
            <i class="fa-solid fa-exclamation-circle"></i>
        </div>
        <div>
            Belum ada shift aktif. Silakan
            <a href="<?php echo e(route('kas.opening.form')); ?>" class="fw-bold text-decoration-underline">buka kasir baru</a> untuk memulai shift.
        </div>
    </div>
    <?php endif; ?>

    
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-filter me-2"></i> Filter Periode</h6>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-lg-4 col-md-5 col-sm-6">
                    <label for="tanggal_awal" class="form-label fw-semibold small mb-2">Dari Tanggal</label>
                    <input type="date" id="tanggal_awal" name="tanggal_awal" class="form-control"
                        value="<?php echo e(request('tanggal_awal', $tanggalAwal->format('Y-m-d'))); ?>">
                </div>
                <div class="col-lg-4 col-md-5 col-sm-6">
                    <label for="tanggal_akhir" class="form-label fw-semibold small mb-2">Sampai Tanggal</label>
                    <input type="date" id="tanggal_akhir" name="tanggal_akhir" class="form-control"
                        value="<?php echo e(request('tanggal_akhir', $tanggalAkhir->format('Y-m-d'))); ?>">
                </div>
                <div class="col-lg-4 col-md-2 col-sm-12">
                    <label class="form-label fw-semibold small mb-2 d-none d-md-block">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="fa-solid fa-search me-1"></i> Terapkan
                        </button>
                        <a href="<?php echo e(route('dashboard.index')); ?>" class="btn btn-outline-secondary flex-fill">
                            <i class="fa-solid fa-rotate me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    
    <div class="mb-4">
        <h5 class="fw-semibold mb-3">
            <i class="fa-solid fa-money-bill-trend-up text-success me-2"></i> Ringkasan Pendapatan
        </h5>
    </div>

    <div class="row g-4 mb-4">
        <?php
            $summary = [
                ['label' => 'Pendapatan Hari Ini', 'icon' => 'calendar-day', 'color' => 'success', 'value' => $pendapatanHariIniTotal ?? 0, 'status' => $statusHari ?? null, 'percent' => $percentHari ?? 0],
                ['label' => 'Pendapatan Bulan Ini', 'icon' => 'calendar-week', 'color' => 'primary', 'value' => $pendapatanBulanIni ?? 0, 'status' => $statusBulan ?? null, 'percent' => $percentBulan ?? 0],
                ['label' => 'Pendapatan Tahun Ini', 'icon' => 'calendar', 'color' => 'info', 'value' => $pendapatanTahunIni ?? 0, 'status' => $statusTahun ?? null, 'percent' => $percentTahun ?? 0],
            ];
        ?>

        <?php $__currentLoopData = $summary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-lg-4 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="flex-grow-1">
                            <p class="text-muted small mb-1"><?php echo e($item['label']); ?></p>
                            <h4 class="fw-bold mb-2">Rp <?php echo e(number_format($item['value'], 0, ',', '.')); ?></h4>
                            <?php if($item['status'] === 'up'): ?>
                                <small class="text-success">
                                    <i class="fa-solid fa-arrow-up"></i> <?php echo e(round($item['percent'],1)); ?>% dari periode sebelumnya
                                </small>
                            <?php elseif($item['status'] === 'down'): ?>
                                <small class="text-danger">
                                    <i class="fa-solid fa-arrow-down"></i> <?php echo e(abs(round($item['percent'],1))); ?>% dari periode sebelumnya
                                </small>
                            <?php else: ?>
                                <small class="text-muted">
                                    <i class="fa-solid fa-minus"></i> 0% dari periode sebelumnya
                                </small>
                            <?php endif; ?>
                        </div>
                        <div class="bg-<?php echo e($item['color']); ?> bg-opacity-10 rounded-3 p-3">
                            <i class="fa-solid fa-<?php echo e($item['icon']); ?> text-<?php echo e($item['color']); ?> fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="row g-4 mb-4">
        <?php
            $stats = [
                ['label' => 'Transaksi Hari Ini', 'icon' => 'receipt', 'color' => 'primary', 'value' => $totalTransaksiHariIni ?? 0, 'suffix' => ''],
                ['label' => 'Item Terjual Hari Ini', 'icon' => 'box', 'color' => 'warning', 'value' => $totalPenjualanHariIni ?? 0, 'suffix' => ' item'],
                ['label' => 'Total Transaksi', 'icon' => 'file-invoice', 'color' => 'secondary', 'value' => $totalTransaksi ?? 0, 'suffix' => ''],
            ];
        ?>

        <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-lg-4 col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1"><?php echo e($s['label']); ?></p>
                            <h4 class="fw-bold mb-0"><?php echo e(number_format($s['value'], 0, ',', '.')); ?><?php echo e($s['suffix']); ?></h4>
                        </div>
                        <div class="bg-<?php echo e($s['color']); ?> bg-opacity-10 p-3 rounded-3">
                            <i class="fa-solid fa-<?php echo e($s['icon']); ?> text-<?php echo e($s['color']); ?> fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="row g-4 mb-4">
        
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-credit-card me-2"></i> Metode Pembayaran Hari Ini</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <div class="p-3 bg-primary bg-opacity-10 rounded-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <small class="text-muted d-block mb-1">Tunai</small>
                                        <h5 class="fw-bold mb-0 text-primary">Rp <?php echo e(number_format($pendapatanTunai ?? 0, 0, ',', '.')); ?></h5>
                                    </div>
                                    <i class="fa-solid fa-money-bill-wave text-primary fs-3"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-info bg-opacity-10 rounded-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <small class="text-muted d-block mb-1">Non Tunai</small>
                                        <h5 class="fw-bold mb-0 text-info">Rp <?php echo e(number_format($pendapatanNonTunai ?? 0, 0, ',', '.')); ?></h5>
                                    </div>
                                    <i class="fa-solid fa-credit-card text-info fs-3"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="height: 200px;">
                        <canvas id="jenisTransaksiChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-star me-2"></i> Produk Terlaris (Top 5)</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="fw-semibold">Ranking</th>
                                    <th class="fw-semibold">Produk</th>
                                    <th class="fw-semibold text-center">Terjual</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $produkTerlaris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <span class="badge <?php echo e($index === 0 ? 'bg-warning' : ($index === 1 ? 'bg-secondary' : 'bg-light text-dark')); ?>">
                                            #<?php echo e($index + 1); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                                <i class="fa-solid fa-box text-primary"></i>
                                            </div>
                                            <span class="fw-semibold"><?php echo e($item->product->name ?? '-'); ?></span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success bg-opacity-10 text-success">
                                            <?php echo e(number_format($item->total_qty ?? 0, 0, ',', '.')); ?> item
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="3" class="text-center py-4">
                                        <i class="fa-solid fa-inbox fs-1 text-muted mb-2 d-block"></i>
                                        <span class="text-muted">Belum ada data produk terlaris</span>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row g-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-chart-line me-2"></i> Tren Pendapatan dalam Rentang Waktu</h6>
                </div>
                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="pendapatanChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php if($showOpeningModal ?? false): ?>
<div class="modal fade" id="kasModal" tabindex="-1" aria-labelledby="kasModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="<?php echo e(route('kas.opening.store')); ?>" method="POST" class="modal-content">
            <?php echo csrf_field(); ?>
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="kasModalLabel"><i class="fa-solid fa-coins"></i> Set Kas Awal</h5>
            </div>
            <div class="modal-body">
                <p>Silakan masukkan jumlah uang kas awal shift ini:</p>
                <input type="number" name="opening_amount" class="form-control" required min="0" placeholder="Nominal kas awal (Rp)">
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan Kas Awal</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Format currency helper
    function formatCurrency(value) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(value);
    }

    // Chart Pendapatan (Line Chart)
    const pendapatanCtx = document.getElementById('pendapatanChart').getContext('2d');
    const gradientPendapatan = pendapatanCtx.createLinearGradient(0, 0, 0, 300);
    gradientPendapatan.addColorStop(0, 'rgba(13, 110, 253, 0.3)');
    gradientPendapatan.addColorStop(1, 'rgba(13, 110, 253, 0.01)');

    new Chart(pendapatanCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($labelsPendapatan ?? []); ?>,
            datasets: [{
                label: 'Pendapatan',
                data: <?php echo json_encode($dataPendapatan ?? []); ?>,
                backgroundColor: gradientPendapatan,
                borderColor: 'rgba(13, 110, 253, 1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointRadius: 6,
                pointHoverRadius: 8,
                pointBackgroundColor: '#fff',
                pointBorderColor: 'rgba(13, 110, 253, 1)',
                pointBorderWidth: 2,
                pointHoverBackgroundColor: 'rgba(13, 110, 253, 1)',
                pointHoverBorderColor: '#fff',
                pointHoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            return 'Pendapatan: ' + formatCurrency(context.raw);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        callback: function(value) {
                            if (value >= 1000000) {
                                return 'Rp ' + (value / 1000000).toFixed(1) + 'Jt';
                            } else if (value >= 1000) {
                                return 'Rp ' + (value / 1000).toFixed(0) + 'K';
                            }
                            return 'Rp ' + value;
                        },
                        font: {
                            size: 11
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                }
            }
        }
    });

    // Chart Jenis Transaksi (Doughnut Chart)
    const jenisCtx = document.getElementById('jenisTransaksiChart').getContext('2d');
    new Chart(jenisCtx, {
        type: 'doughnut',
        data: {
            labels: ['Tunai', 'Non Tunai'],
            datasets: [{
                data: [<?php echo e($pendapatanTunai ?? 0); ?>, <?php echo e($pendapatanNonTunai ?? 0); ?>],
                backgroundColor: ['rgba(13, 110, 253, 0.8)', 'rgba(13, 202, 240, 0.8)'],
                borderColor: ['#fff', '#fff'],
                borderWidth: 3,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: {
                            size: 12,
                            weight: '500'
                        },
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            let value = context.raw || 0;
                            let total = context.dataset.data.reduce((a, b) => a + b, 0);
                            let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return label + ': ' + formatCurrency(value) + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    <?php if($showOpeningModal ?? false): ?>
        document.addEventListener("DOMContentLoaded", function () {
            var kasModal = new bootstrap.Modal(document.getElementById('kasModal'));
            kasModal.show();
        });
    <?php endif; ?>
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projek_pos\qassir_na\resources\views/dashboard.blade.php ENDPATH**/ ?>