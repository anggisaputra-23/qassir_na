<?php $__env->startSection('title', 'Daftar Karyawan'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0"><i class="fa-solid fa-users me-2 text-primary"></i> Daftar Karyawan</h3>
    <?php if(auth()->user()->role === 'owner'): ?>
        <a href="<?php echo e(route('karyawan.create')); ?>" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> Tambah Karyawan
        </a>
    <?php endif; ?>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i><?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-circle-exclamation me-2"></i><?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <?php if($users->count() > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="fw-semibold">#</th>
                        <th class="fw-semibold">Nama</th>
                        <th class="fw-semibold">Email</th>
                        <th class="fw-semibold" style="width:200px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($loop->iteration + ($users->currentPage() - 1) * $users->perPage()); ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                    <i class="fa-solid fa-user text-primary"></i>
                                </div>
                                <span class="fw-semibold"><?php echo e($user->name); ?></span>
                            </div>
                        </td>
                        <td><?php echo e($user->email); ?></td>
                        <td>
                            <?php if(auth()->user()->role === 'owner'): ?>
                                <a href="<?php echo e(route('karyawan.edit', $user->id)); ?>" class="btn btn-sm btn-warning">
                                    <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                                </a>
                                <form action="<?php echo e(route('karyawan.destroy', $user->id)); ?>" method="POST" style="display:inline-block;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus karyawan ini?')">
                                        <i class="fa-solid fa-trash me-1"></i> Hapus
                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="text-muted small">Tidak ada aksi</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <?php echo e($users->links()); ?>

        </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fa-solid fa-users-slash fs-1 text-muted mb-3"></i>
                <p class="text-muted">Belum ada data karyawan</p>
                <?php if(auth()->user()->role === 'owner'): ?>
                    <a href="<?php echo e(route('karyawan.create')); ?>" class="btn btn-primary mt-2">
                        <i class="fa-solid fa-plus me-1"></i> Tambah Karyawan Pertama
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projek_pos\qassir_na\resources\views\users\index.blade.php ENDPATH**/ ?>