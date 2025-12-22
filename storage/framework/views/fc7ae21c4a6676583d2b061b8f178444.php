<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Transaksi Tersimpan (Open Bill)</h3>
    <a href="<?php echo e(route('transaksi.index')); ?>" class="btn btn-outline-primary"><i class="fa-solid fa-cash-register me-1"></i> Kembali ke Transaksi</a>
    </div>

<div class="card shadow-sm">
  <div class="card-body">
    <?php if($bills->isEmpty()): ?>
      <div class="text-center text-muted py-4">Belum ada transaksi tersimpan.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>Kode</th>
              <th>Kasir</th>
              <th>Nama / Meja</th>
              <th>Jenis</th>
              <th>Total</th>
              <th>Dibuat</th>
              <th style="width:180px">Aksi</th>
            </tr>
          </thead>
          <tbody id="openBillTableBody">
            <?php $__currentLoopData = $bills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr data-id="<?php echo e($bill->id); ?>">
                <td><?php echo e($bill->bill_code); ?></td>
                <td>
                  <span class="badge bg-info"><?php echo e($bill->user->name ?? '-'); ?></span>
                </td>
                <td><?php echo e($bill->customer_name ?: '-'); ?></td>
                <td><?php echo e($bill->order_type === 'take_away' ? 'Take Away' : 'Dine In'); ?></td>
                <td>Rp <?php echo e(number_format($bill->total,0,',','.')); ?></td>
                <td><?php echo e($bill->created_at ? $bill->created_at->format('d M Y H:i') : '-'); ?></td>
                <td>
                  <button class="btn btn-sm btn-success btn-load"><i class="fa-solid fa-arrow-right"></i> Lanjutkan</button>
                  <button class="btn btn-sm btn-danger btn-delete"><i class="fa-solid fa-trash"></i></button>
                </td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
  const csrf = '<?php echo e(csrf_token()); ?>';
  const body = document.getElementById('openBillTableBody');

  body.querySelectorAll('.btn-load').forEach(btn => {
    btn.onclick = function(){
      const id = btn.closest('tr').dataset.id;
      fetch("<?php echo e(route('transaksi.openBill.load', ['id' => '__ID__'])); ?>".replace('__ID__', id), {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
      })
      .then(r => r.json())
      .then(json => {
        if(json.success){
          Swal.fire({ icon: 'success', title: 'Dimuat', timer: 800, showConfirmButton: false })
            .then(() => window.location.href = "<?php echo e(route('transaksi.index')); ?>");
        } else {
          Swal.fire({ icon: 'error', title: 'Gagal memuat', text: json.message || 'Coba lagi.' });
        }
      }).catch(() => Swal.fire({ icon: 'error', title: 'Gagal menghubungi server' }));
    };
  });

  body.querySelectorAll('.btn-delete').forEach(btn => {
    btn.onclick = function(){
      const tr = btn.closest('tr');
      const id = tr.dataset.id;
      Swal.fire({
        title: 'Hapus transaksi tersimpan?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal'
      }).then(res => {
        if(!res.isConfirmed) return;
        fetch("<?php echo e(route('transaksi.openBill.destroy', ['id' => '__ID__'])); ?>".replace('__ID__', id), {
          method: 'DELETE',
          headers: { 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        }).then(r => r.json()).then(json => {
          if(json.success){
            tr.remove();
            Swal.fire({ icon: 'success', title: 'Dihapus', timer: 800, showConfirmButton: false });
          } else {
            Swal.fire({ icon: 'error', title: 'Gagal menghapus' });
          }
        }).catch(() => Swal.fire({ icon: 'error', title: 'Gagal menghubungi server' }));
      });
    };
  });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projek_pos\qassir_na\resources\views/transactions/open_bills.blade.php ENDPATH**/ ?>