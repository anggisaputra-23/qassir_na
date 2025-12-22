<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Struk Pembayaran</title>
    <style>
        body {
            font-family: monospace, sans-serif;
            font-size: 12px;
            width: 58mm; /* Bisa ganti 80mm */
            margin: 0 auto;
        }

        .receipt {
            width: 100%;
            text-align: center;
        }

        .title {
            font-size: 14px;
            font-weight: bold;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        table {
            width: 100%;
            font-size: 12px;
            border-collapse: collapse;
        }

        td, th {
            padding: 2px 0;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .footer {
            margin-top: 10px;
            text-align: center;
            font-size: 11px;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            @page {
                size: auto;
                margin: 0;
            }
        }
    </style>
</head>
<body onload="window.print()">

<div class="receipt">
    <div class="title">Qassir NA</div>
    <div>Jl. Contoh No. 123</div>
    <div class="line"></div>

    <table>
        <tr>
            <td colspan="3">Kasir: <?php echo e(auth()->user()->name ?? 'Admin'); ?></td>
        </tr>
        <tr>
            <td colspan="3">Tanggal: <?php echo e(date('d-m-Y H:i')); ?></td>
        </tr>
    </table>

    <div class="line"></div>

    <table>
        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                // Hitung subtotal setelah diskon
                $itemSubtotal = $item->quantity * $item->price;
                if ($item->discount > 0) {
                    if ($item->discount_type === 'percent') {
                        $diskonNominal = ($item->discount / 100) * $itemSubtotal;
                    } else {
                        $diskonNominal = $item->discount;
                    }
                    $itemSubtotal -= $diskonNominal;
                }
            ?>
            <tr>
                <td colspan="3"><?php echo e($item->product->name); ?></td>
            </tr>
            <tr>
                <td class="right"><?php echo e(number_format($item->quantity, 0)); ?> x <?php echo e(number_format($item->price, 0)); ?></td>
                <td class="right" colspan="2"><?php echo e(number_format($itemSubtotal, 0)); ?></td>
            </tr>
            <?php if($item->discount > 0): ?>
                <tr>
                    <td colspan="3" class="right" style="font-size:11px; color:red;">
                        Diskon:
                        <?php if($item->discount_type === 'percent'): ?>
                            <?php echo e($item->discount); ?>%
                            ( -<?php echo e(number_format($diskonNominal, 0)); ?> )
                        <?php else: ?>
                            Rp <?php echo e(number_format($item->discount, 0)); ?>

                        <?php endif; ?>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </table>

    <div class="line"></div>

    <table>
        <tr>
            <td><b>Total</b></td>
            <td class="right" colspan="2"><b><?php echo e(number_format($total, 0)); ?></b></td>
        </tr>
        <tr>
            <td>Bayar</td>
            <td class="right" colspan="2"><?php echo e(number_format($bayar, 0)); ?></td>
        </tr>
        <tr>
            <td>Kembali</td>
            <td class="right" colspan="2"><?php echo e(number_format($kembali, 0)); ?></td>
        </tr>
    </table>

    <div class="line"></div>

    <div class="footer">
        Terima kasih atas kunjungan Anda<br>
        Barang yang sudah dibeli tidak dapat dikembalikan
    </div>
</div>

</body>
</html>
<?php /**PATH D:\projek_pos\qassir_na\resources\views\receipt.blade.php ENDPATH**/ ?>