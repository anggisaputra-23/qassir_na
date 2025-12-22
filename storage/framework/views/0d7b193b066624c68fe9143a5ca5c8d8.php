<?php $__env->startSection('content'); ?>
<div class="container py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">
            <i class="fa-solid fa-boxes-stacked me-2 text-success"></i> Daftar Produk
        </h4>
        <button class="btn btn-success shadow-sm" id="btnAddProduct" data-bs-toggle="modal" data-bs-target="#productModal">
            <i class="fa-solid fa-plus me-1"></i> Tambah Produk
        </button>
    </div>

    
    <div class="row mb-4 g-2">
        <div class="col-md-8">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fa-solid fa-search text-muted"></i>
                </span>
                <input type="text" id="searchProduct" class="form-control border-start-0" placeholder="Cari produk berdasarkan nama...">
            </div>
        </div>
        <div class="col-md-4">
            <select id="filterCategory" class="form-select shadow-sm">
                <option value="">Semua Kategori</option>
                <option value="Makanan">Makanan</option>
                <option value="Minuman">Minuman</option>
            </select>
        </div>
    </div>

    
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light">
                    <tr class="text-center">
                        <th width="10%">Gambar</th>
                        <th class="text-start">Nama Produk</th>
                        <th width="15%">Kategori</th>
                        <th width="15%">Harga</th>
                        <th width="10%">Stok</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody id="productTableBody">
                    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr data-name="<?php echo e(strtolower($product->name)); ?>" data-category="<?php echo e($product->category); ?>">
                        <td class="text-center">
                            <img src="<?php echo e($product->image ? asset('storage/'.$product->image) : 'https://via.placeholder.com/60x60?text=No+Image'); ?>"
                                 alt="<?php echo e($product->name); ?>"
                                 class="rounded shadow-sm border" width="60" height="60" style="object-fit: cover;">
                        </td>
                        <td class="fw-semibold text-start"><?php echo e($product->name); ?></td>
                        <td class="text-center">
                            <span class="badge bg-<?php echo e($product->category === 'Makanan' ? 'primary' : 'warning'); ?>">
                                <?php echo e($product->category); ?>

                            </span>
                        </td>
                        <td class="text-center">Rp <?php echo e(number_format((int) $product->price, 0, ',', '.')); ?></td>
                        <td class="text-center">
                            <?php if($product->has_stock): ?>
                                    <?php $st = (int) ($product->stock ?? 0); ?>
                                    <?php if($st <= 0): ?>
                                        <span class="badge bg-danger">Habis</span>
                                    <?php else: ?>
                                        <?php echo e($st); ?>

                                    <?php endif; ?>
                            <?php else: ?>
                                <span class="text-muted">–</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary btn-edit"
                                        data-id="<?php echo e($product->id); ?>"
                                        data-name="<?php echo e($product->name); ?>"
                                        data-price="<?php echo e((int) $product->price); ?>"
                                        data-category="<?php echo e($product->category); ?>"
                                        data-image="<?php echo e($product->image); ?>"
                                        data-has_stock="<?php echo e($product->has_stock); ?>"
                                        data-stock="<?php echo e($product->stock); ?>"
                                        title="Edit Produk">
                                    <i class="fa-solid fa-pen"></i>
                                </button>

                                <form action="<?php echo e(route('produk.destroy', $product->id)); ?>"
                                      method="POST"
                                      class="delete-form d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Produk">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="fa-solid fa-box-open fa-lg mb-2 d-block"></i>
                            Belum ada produk yang tersedia.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="productForm" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="old_image" id="oldImage">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalTitle">Tambah Produk</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="productId" name="productId">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Produk</label>
                        <input type="text" class="form-control" id="productName" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kategori</label>
                        <select class="form-select" id="productCategory" name="category" required>
                            <option value="" disabled selected>Pilih kategori</option>
                            <option value="Makanan">Makanan</option>
                            <option value="Minuman">Minuman</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Harga</label>
                        <input type="number" class="form-control" id="productPrice" name="price" min="0" step="1" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Apakah produk memiliki stok?</label>
                        <select class="form-select" id="hasStock" name="has_stock" required>
                            <option value="0">Tidak</option>
                            <option value="1">Ya</option>
                        </select>
                    </div>

                    <div class="mb-3" id="stockField" style="display:none;">
                        <label class="form-label fw-semibold">Jumlah Stok</label>
                        <input type="number" class="form-control" id="productStock" name="stock" min="0" step="1">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Gambar Produk</label>
                        <input type="file" class="form-control" id="productImage" name="image" accept="image/*">
                        <div id="currentImage" class="mt-3 text-center"></div>
                        <button type="button" class="btn btn-sm btn-danger mt-2" id="removeImage" style="display:none;">Hapus Gambar</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success" id="saveProductBtn">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchProduct');
    const filterSelect = document.getElementById('filterCategory');
    const rows = document.querySelectorAll('#productTableBody tr[data-name]');
    const hasStockSelect = document.getElementById('hasStock');
    const stockField = document.getElementById('stockField');
    const removeImageBtn = document.getElementById('removeImage');
    const oldImageInput = document.getElementById('oldImage');
    const currentImageDiv = document.getElementById('currentImage');

    // === tampilkan field stok jika "Ya" ===
    hasStockSelect.addEventListener('change', function() {
        stockField.style.display = this.value === '1' ? 'block' : 'none';
    });

    // Filter nama & kategori
    function filterProducts() {
        const keyword = searchInput.value.toLowerCase();
        const selectedCategory = filterSelect.value;

        rows.forEach(row => {
            const name = row.dataset.name;
            const category = row.dataset.category;
            const matchName = name.includes(keyword);
            const matchCategory = !selectedCategory || category === selectedCategory;
            row.style.display = matchName && matchCategory ? '' : 'none';
        });
    }

    searchInput.addEventListener('keyup', filterProducts);
    filterSelect.addEventListener('change', filterProducts);

    const modal = new bootstrap.Modal(document.getElementById('productModal'));
    const form = document.getElementById('productForm');
    const idInput = document.getElementById('productId');
    const nameInput = document.getElementById('productName');
    const categorySelect = document.getElementById('productCategory');
    const priceInput = document.getElementById('productPrice');
    const stockInput = document.getElementById('productStock');

    // Tambah Produk
    document.getElementById('btnAddProduct').addEventListener('click', function() {
        form.action = "<?php echo e(route('produk.store')); ?>";
        idInput.value = '';
        nameInput.value = '';
        priceInput.value = '';
        categorySelect.value = '';
        hasStockSelect.value = '0';
        stockField.style.display = 'none';
        stockInput.value = '';
        currentImageDiv.innerHTML = '';
        removeImageBtn.style.display = 'none';
        oldImageInput.value = '';
        const methodField = form.querySelector('input[name="_method"]');
        if(methodField) methodField.remove();
        document.getElementById('modalTitle').textContent = 'Tambah Produk';
    });

    // Edit Produk
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            modal.show();
            document.getElementById('modalTitle').textContent = 'Edit Produk';
            form.action = `/produk/${id}`;

            let methodInput = form.querySelector('input[name="_method"]');
            if(!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                form.appendChild(methodInput);
            }
            methodInput.value = 'PUT';

            idInput.value = id;
            nameInput.value = this.dataset.name;
            priceInput.value = this.dataset.price;
            categorySelect.value = this.dataset.category;
            hasStockSelect.value = this.dataset.has_stock;
            if (this.dataset.has_stock == '1') {
                stockField.style.display = 'block';
                stockInput.value = this.dataset.stock;
            } else {
                stockField.style.display = 'none';
                stockInput.value = '';
            }

            if(this.dataset.image) {
                currentImageDiv.innerHTML = `<img src="/storage/${this.dataset.image}" width="100" class="rounded shadow-sm border">`;
                removeImageBtn.style.display = 'inline-block';
                oldImageInput.value = this.dataset.image;
            } else {
                currentImageDiv.innerHTML = '';
                removeImageBtn.style.display = 'none';
                oldImageInput.value = '';
            }
        });
    });

    // Hapus gambar lama
    removeImageBtn.addEventListener('click', function() {
        currentImageDiv.innerHTML = '';
        oldImageInput.value = '';
        this.style.display = 'none';
    });

    // Konfirmasi hapus produk
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Yakin hapus produk ini?',
                text: "Data tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Flash sukses
    <?php if(session('success')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "<?php echo e(session('success')); ?>",
            showConfirmButton: false,
            timer: 2000
        });
    <?php endif; ?>
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projek_pos\qassir_na\resources\views\products\index.blade.php ENDPATH**/ ?>