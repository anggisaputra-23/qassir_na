<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <h3><i class="fa-solid fa-door-open"></i> Buka Kasir</h3>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success mt-3"><?php echo e(session('success')); ?></div>
    <?php elseif(session('error')): ?>
        <div class="alert alert-danger mt-3"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <form action="<?php echo e(route('kas.opening.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label for="opening_amount" class="form-label fw-bold">Kas Awal (Rp)</label>
                    <input type="number" name="opening_amount" id="opening_amount" class="form-control" required min="0" placeholder="Masukkan jumlah uang kas awal">
                </div>

                <div class="alert alert-info p-2">
                    <i class="fa-solid fa-circle-info"></i>
                    Setiap shift baru wajib memasukkan kas awal, meskipun di hari yang sama.
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="fa-solid fa-check"></i> Simpan & Buka Kasir
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projek_pos\qassir_na\resources\views/cash/opening.blade.php ENDPATH**/ ?>