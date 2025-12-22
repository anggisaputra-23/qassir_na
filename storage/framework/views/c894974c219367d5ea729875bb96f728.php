<?php $__env->startSection('content'); ?>
<div class="container-fluid px-2 px-md-4">
    <div class="row g-3 g-lg-4">
        
        <div class="col-12 col-lg-8">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-md-items-center gap-2 mb-4">
                <h3 class="mb-0" style="font-weight: 700; color: #212529;">Menu</h3>
                <div class="d-flex gap-2 w-100 w-md-auto flex-wrap align-items-center">
                    <div class="input-group flex-grow-1" style="min-width: 180px; max-width: 300px;">
                        <span class="input-group-text bg-white border-1 rounded-start-2">
                            <i class="fa-solid fa-magnifying-glass text-muted"></i>
                        </span>
                        <input type="text" id="searchMenu" class="form-control border-1 rounded-end-2" placeholder="Cari menu...">
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary rounded-2 dropdown-toggle" type="button" id="filterCategoryBtn" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.85rem; padding: 0.5rem 0.75rem;">
                            <i class="fa-solid fa-filter me-1"></i> Kategori
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterCategoryBtn" style="min-width: 150px;">
                            <li><a class="dropdown-item category-filter" href="#" data-value="" style="cursor: pointer;">Semua Kategori</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item category-filter" href="#" data-value="Makanan" style="cursor: pointer;">Makanan</a></li>
                            <li><a class="dropdown-item category-filter" href="#" data-value="Minuman" style="cursor: pointer;">Minuman</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row g-2 g-md-3" id="menuList">
        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $isOut = $product->has_stock && (int)($product->stock ?? 0) <= 0;
                $isLowStock = $product->has_stock && (int)($product->stock ?? 0) > 0 && (int)($product->stock ?? 0) <= 5;
            ?>
            <div class="col-6 col-sm-4 col-md-4 col-lg-3 menu-item"
                data-id="<?php echo e($product->id); ?>"
                data-name="<?php echo e(strtolower($product->name)); ?>"
                data-category="<?php echo e(strtolower($product->category ?? '')); ?>"
                data-has-stock="<?php echo e($product->has_stock ? 1 : 0); ?>"
                data-stock="<?php echo e((int)($product->stock ?? 0)); ?>">
            <div class="card menu-card h-100 shadow-sm border-0 rounded-3 overflow-hidden position-relative <?php echo e($isOut ? 'opacity-75' : ''); ?>" style="transition: transform 0.2s ease, box-shadow 0.2s ease; cursor: pointer;">
                <?php if($product->image): ?>
                <img src="<?php echo e(asset('storage/'.$product->image)); ?>"
                     class="card-img-top"
                     style="height:120px; width:100%; object-fit:cover;">
                <?php else: ?>
                <img src="https://via.placeholder.com/300x150?text=No+Image"
                     class="card-img-top"
                     style="height:120px; width:100%; object-fit:cover;">
                <?php endif; ?>
                <?php if($isOut): ?>
                <span class="position-absolute top-0 start-0 m-2 badge bg-danger rounded-pill" style="font-size: 0.75rem;">
                    <i class="fa-solid fa-ban me-1"></i>Habis
                </span>
                <?php elseif($isLowStock): ?>
                <span class="position-absolute top-0 start-0 m-2 badge bg-warning text-dark rounded-pill" style="font-size: 0.75rem;">
                    <i class="fa-solid fa-exclamation-triangle me-1"></i>Stok <?php echo e((int)($product->stock ?? 0)); ?>

                </span>
                <?php endif; ?>
                <div class="card-body text-center py-2">
                    <h6 class="card-title fw-bold mb-1 text-truncate" style="font-size: 0.85rem; color: #212529;"><?php echo e($product->name); ?></h6>
                    <p class="text-success fw-semibold mb-0" style="font-size: 0.9rem;">Rp <?php echo e(number_format($product->price,0)); ?></p>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

        </div>

        
        <div class="col-12 col-lg-4">
            <div class="card shadow-lg rounded-3 border-0" style="position: sticky; top: 16px; z-index: 1000; max-height: calc(100vh - 32px); display: flex; flex-direction: column; overflow: hidden;">
                
                <div class="card-header bg-gradient bg-primary text-white d-flex justify-content-between align-items-center rounded-top-3 p-3 flex-shrink-0">
                    <div>
                        <h5 class="mb-0" style="font-size: 1.1rem; font-weight: 700;">
                            <i class="fa-solid fa-cart-shopping me-2"></i> Keranjang
                        </h5>
                        <small class="text-white-50"><span id="itemCount">0</span> item</small>
                    </div>
                    <button type="button" id="openBillListBtn"
                            class="btn btn-light btn-sm rounded-2"
                            data-bs-toggle="modal"
                            data-bs-target="#openBillModal">
                        <i class="fa-solid fa-file-invoice me-1"></i> <span class="d-none d-sm-inline">Tersimpan</span>
                    </button>
                </div>

                
                <div class="card-body d-flex flex-column p-3" style="flex: 1; overflow-y: auto; gap: 0.5rem;">

                    
                    <div class="mb-2">
                        <label class="form-label small fw-semibold text-muted">Pelanggan / Meja</label>
                        <input type="text"
                               id="customerName"
                               class="form-control rounded-2 border-1"
                               placeholder="Nama atau no meja"
                               value="<?php echo e($cartOrderInfo['customer_name'] ?? ''); ?>"
                               style="font-size: 0.9rem; padding: 0.5rem 0.6rem;">
                    </div>

                    
                    <div class="mb-2">
                        <label class="form-label small fw-semibold text-muted">Jenis Pesanan</label>
                        <select id="orderType" class="form-select rounded-2 border-1" style="font-size: 0.9rem; padding: 0.5rem 0.6rem;">
                            <option value="dine_in" <?php echo e(($cartOrderInfo['order_type'] ?? '') === 'dine_in' ? 'selected' : ''); ?>>Dine In</option>
                            <option value="take_away" <?php echo e(($cartOrderInfo['order_type'] ?? '') === 'take_away' ? 'selected' : ''); ?>>Take Away</option>
                        </select>
                    </div>

                    
                    <div class="mb-2">
                        <label class="form-label small fw-semibold text-muted">Diskon Global (Opsional)</label>
                        <div class="input-group rounded-2" style="overflow: hidden;">
                            <input type="number" id="discountValue"
                                   class="form-control border-1"
                                   placeholder="0"
                                   value="<?php echo e($cartDiscount['value'] ?? 0); ?>"
                                   style="font-size: 0.9rem; padding: 0.5rem 0.6rem; border-radius: 0.3rem 0 0 0.3rem;">
                            <select id="discountType" class="form-select border-1" style="max-width: 75px; font-size: 0.9rem; padding: 0.5rem;">
                                <option value="percent" <?php echo e(($cartDiscount['type'] ?? '') === 'percent' ? 'selected' : ''); ?>>%</option>
                                <option value="nominal" <?php echo e(($cartDiscount['type'] ?? '') === 'nominal' ? 'selected' : ''); ?>>Rp</option>
                            </select>
                            <button id="applyDiscount" class="btn btn-success rounded-0" style="padding: 0.5rem 0.6rem; font-weight: 600; border-radius: 0 0.3rem 0.3rem 0;">
                                <i class="fa-solid fa-check"></i>
                            </button>
                        </div>
                    </div>

                    <hr class="my-1" style="opacity: 0.5;">

                    
                    <div class="flex-grow-1 mb-3" style="overflow-y: auto; min-height: 250px;">
                        <div id="emptyCartMsg" class="text-center py-4">
                            <i class="fa-solid fa-cart-shopping fa-2x text-muted mb-2 d-block"></i>
                            <p class="text-muted mb-0" style="font-size: 0.9rem;">Keranjang kosong</p>
                        </div>
                        <ul class="list-group list-group-flush" id="cartList">
                            
                        </ul>
                    </div>

                    <hr class="my-2">

                </div>

                
                <div class="card-footer bg-white p-3 flex-shrink-0 border-top">
                    
                    <div class="bg-light rounded-3 p-3 mb-3">
                        <div class="d-flex justify-content-between mb-2" style="font-size: 0.9rem;">
                            <span class="text-muted">Total Asli</span>
                            <span id="originalTotal" class="fw-semibold">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-0 text-dark">Total Bayar</h6>
                            <h5 class="mb-0 fw-bold text-success" id="cartTotal">Rp 0</h5>
                        </div>
                    </div>

                    
                    <div class="d-grid gap-2">
                        <button id="proceedPaymentBtn" class="btn btn-success rounded-2 fw-semibold shadow-sm" style="padding: 12px 12px; font-size: 0.95rem; border-radius: 0.5rem !important;">
                            <i class="fa-solid fa-credit-card me-2"></i> <span class="d-none d-sm-inline">Pembayaran</span>
                        </button>
                        <div class="d-grid gap-2" style="grid-template-columns: 1fr 1fr;">
                            <button id="saveOpenBillBtn" class="btn btn-outline-warning rounded-2 fw-semibold" style="padding: 10px; font-size: 0.9rem; border-radius: 0.5rem !important;">
                                <i class="fa-solid fa-folder-open me-1"></i> <span class="d-none d-sm-inline">Simpan</span>
                            </button>
                            <button id="clearCartBtn" class="btn btn-outline-danger rounded-2 fw-semibold" style="padding: 10px; font-size: 0.9rem; border-radius: 0.5rem !important;">
                                <i class="fa-solid fa-trash me-1"></i> <span class="d-none d-sm-inline">Kosongkan</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content shadow-lg border-0 rounded-3">

      
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">
          <i class="fa-solid fa-credit-card me-2"></i> Pembayaran
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      
      <div class="modal-body">

        
        <div class="mb-3">
          <h5>Total Bayar:
            <span id="paymentTotal" class="fw-bold text-success">Rp 0</span>
          </h5>
        </div>

        
        <div class="mb-3">
          <label class="form-label">Metode Pembayaran</label>
          <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-success flex-fill" onclick="setPaymentMethod('cash')">
              <i class="fa-solid fa-money-bill-wave me-1"></i> Tunai
            </button>
            <button type="button" class="btn btn-outline-primary flex-fill" onclick="setPaymentMethod('non_cash')">
              <i class="fa-solid fa-credit-card me-1"></i> Non Tunai
            </button>
          </div>
          <input type="hidden" id="paymentMethod" name="payment_method" value="cash">
        </div>

        
        <div class="mb-3" id="cashPaymentSection">
          <label class="form-label">Uang Bayar</label>
          <div class="d-flex flex-wrap gap-2 mb-2" id="quickPayButtons"></div>
          <input type="number" id="manualPay" class="form-control" placeholder="Masukkan nominal bayar">
        </div>

        
        <div class="alert alert-info p-2" id="changeSection">
          <strong>Kembalian:</strong> <span id="paymentChange">Rp 0</span>
        </div>
      </div>

      
      <div class="modal-footer">
        <button id="confirmPaymentBtn" class="btn btn-success w-100">
          <i class="fa-solid fa-check-circle me-1"></i> Konfirmasi Pembayaran
        </button>
      </div>
    </div>
  </div>
</div>


<script>
  function setPaymentMethod(method) {
    document.getElementById('paymentMethod').value = method;

    if (method === 'cash') {
      document.getElementById('cashPaymentSection').style.display = 'block';
      document.getElementById('changeSection').style.display = 'block';
    } else {
      document.getElementById('cashPaymentSection').style.display = 'none';
      document.getElementById('changeSection').style.display = 'none';
    }
  }

  // Default ke Tunai
  setPaymentMethod('cash');
</script>



<div class="modal fade" id="transactionDetailModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">Struk Pembayaran</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="transactionDetailBody">
        
      </div>
      <div class="modal-footer">
        <button onclick="window.print()" class="btn btn-dark w-100">
          <i class="fa-solid fa-print"></i> Cetak
        </button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="openBillModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-3">

            <div class="modal-header bg-primary text-white rounded-top-3">
                <h5 class="modal-title">
                    <i class="fa-solid fa-folder-open me-2"></i> Transaksi Tersimpan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <table class="table table-hover align-middle" id="openBillTable">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 28%">Nama / Meja</th>
                            <th style="width: 18%">Total</th>
                            <th style="width: 20%">Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="openBillTableBody">
                        
                    </tbody>

                </table>

            </div>

        </div>
    </div>
</div>

<style>
/* Menu card hover effect */
.menu-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.15) !important;
}

.menu-card {
    border-radius: 0.75rem !important;
}

/* Category dropdown styling */
.dropdown-menu {
    border-radius: 0.5rem;
    border: 1px solid #e9ecef;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
}

.dropdown-item:hover,
.dropdown-item:focus {
    background-color: #f1f3f5;
    color: #212529;
}

.category-filter.active {
    background-color: #e7f1ff;
    color: #0d6efd;
}

/* Cart item styling improvements */
.list-group-item {
    background-color: #fff;
    border: 1px solid #f0f0f0 !important;
    border-radius: 0.5rem !important;
}

.list-group-item:hover {
    background-color: #f9f9f9;
}

/* Button improvements */
.btn-outline-warning:hover {
    background-color: #ffc107;
    color: #000 !important;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    color: #fff !important;
}
</style>


<style>
/* Khusus print thermal 58mm / 80mm */
@media print {
  body * {
    visibility: hidden;
  }
  #receiptArea, #receiptArea * {
    visibility: visible;
  }
  #receiptArea {
    position: absolute;
    left: 0;
    top: 0;
    width: 58mm;
    font-size: 11px;
    line-height: 1.2em;
  }
  #receiptArea table {
    width: 100%;
    border-collapse: collapse;
  }
  #receiptArea th, #receiptArea td {
    text-align: left;
    font-size: 11px;
    padding: 2px 0;
  }
}
</style>

<!-- SweetAlert2 untuk pop-up yang modern -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Init from server
    let cart = <?php echo json_encode($cart ?? [], 15, 512) ?>;
    const serverDiscount = <?php echo json_encode($cartDiscount ?? ['value'=>0, 'type'=>'percent'], 512) ?>;
    const serverOrderInfo = <?php echo json_encode($cartOrderInfo ?? ['customer_name'=>'', 'order_type'=>'dine_in'], 512) ?>;
    const openBillsFromServer = <?php echo json_encode($openBills ?? [], 15, 512) ?>;

    const cartList = document.getElementById('cartList');
    const originalTotalElem = document.getElementById('originalTotal');
    const cartTotalElem = document.getElementById('cartTotal');
    const discountValue = document.getElementById('discountValue');
    const discountType = document.getElementById('discountType');
    const applyDiscountBtn = document.getElementById('applyDiscount');
    const manualPay = document.getElementById('manualPay');
    const quickPayButtons = document.getElementById('quickPayButtons');
    const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
    const paymentTotalElem = document.getElementById('paymentTotal');
    const paymentChangeElem = document.getElementById('paymentChange');
    const confirmPaymentBtn = document.getElementById('confirmPaymentBtn');
    const transactionDetailModal = new bootstrap.Modal(document.getElementById('transactionDetailModal'));
    const transactionDetailBody = document.getElementById('transactionDetailBody');
    const proceedPaymentBtn = document.getElementById('proceedPaymentBtn');
    const saveOpenBillBtn = document.getElementById('saveOpenBillBtn');
    const customerNameInput = document.getElementById('customerName');
    const orderTypeSelect = document.getElementById('orderType');
    const openBillModal = new bootstrap.Modal(document.getElementById('openBillModal'));
    const openBillListBtn = document.getElementById('openBillListBtn');
    const openBillTableBody = document.querySelector('#openBillTable tbody');
    const searchMenuInput = document.getElementById('searchMenu');
    const filterCategorySelect = document.getElementById('filterCategory');
    const menuList = document.getElementById('menuList');

    // set server initial
    discountValue.value = serverDiscount.value ?? 0;
    discountType.value = serverDiscount.type ?? 'percent';
    customerNameInput.value = serverOrderInfo.customer_name ?? '';
    orderTypeSelect.value = serverOrderInfo.order_type ?? 'dine_in';

    function escapeHtml(str){
        if(!str && str !== 0) return '';
        return String(str).replace(/[&<>"'`=\/]/g, function(s){ return ({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','/':'&#x2F;','`':'&#x60;','=':'&#x3D;' })[s]; });
    }

    function calculateTotalsLocal(){
        let totalOriginal = 0, totalFinal = 0;
        for(const [id, item] of Object.entries(cart)){
            const price = Number(item.price) || 0;
            const qty = Number(item.quantity) || 0;
            const discVal = Number(item.discount || 0);
            const discType = item.discount_type || 'nominal';
            const subtotal = price * qty;
            totalOriginal += subtotal;

            let discAmount = 0;
            if (discVal > 0) {
                if (discType === 'percent') discAmount = subtotal * discVal / 100;
                else discAmount = discVal;
            }
            totalFinal += Math.max(0, subtotal - discAmount);
        }

        // apply global discount
        const globalDiscount = { value: Number(discountValue.value)||0, type: discountType.value || 'percent' };
        let final = totalFinal;
        if(globalDiscount.value > 0){
            if(globalDiscount.type === 'percent'){
                final = Math.max(0, final - (final * globalDiscount.value / 100));
            } else {
                final = Math.max(0, final - globalDiscount.value);
            }
        }
        return { totalOriginal, totalFinal: final };
    }

    function renderCart(){
        let html = '';
        const totals = calculateTotalsLocal();
        const itemCount = Object.keys(cart).length;

        // Update item count badge
        document.getElementById('itemCount').textContent = itemCount;

        // Toggle empty message
        const emptyMsg = document.getElementById('emptyCartMsg');
        if (itemCount === 0) {
            emptyMsg.style.display = 'block';
            html = '';
        } else {
            emptyMsg.style.display = 'none';
            for(const [id, item] of Object.entries(cart)){
                const price = Number(item.price) || 0;
                const qty = Number(item.quantity) || 0;
                const subtotal = price * qty;
                const itemDiscVal = Number(item.discount || 0);
                const itemDiscType = item.discount_type || 'nominal';
                let itemDiscAmount = 0;
                if (itemDiscVal > 0) {
                    if (itemDiscType === 'percent') itemDiscAmount = subtotal * itemDiscVal / 100;
                    else itemDiscAmount = itemDiscVal;
                }
                const final = Math.max(0, subtotal - itemDiscAmount);

                html += `<li class="list-group-item border-0 px-0 py-3" data-id="${escapeHtml(id)}">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="flex-grow-1">
                            <strong class="d-block text-dark">${escapeHtml(item.name)}</strong>
                            <small class="text-muted">Rp ${price.toLocaleString('id-ID')} × ${qty}</small>
                            ${item.note ? `<br><small class="text-info"><i class="fa-solid fa-note-sticky me-1"></i>${escapeHtml(item.note)}</small>` : ''}
                        </div>
                        <span class="fw-bold text-success">Rp ${final.toLocaleString('id-ID')}</span>
                    </div>

                    
                    <div class="d-flex gap-2 align-items-center flex-wrap mb-2">
                        <div class="btn-group btn-group-sm" role="group">
                            <button class="btn btn-outline-secondary btn-decrease" title="Kurangi">−</button>
                            <input type="number" min="1" class="form-control form-control-sm quantity text-center" value="${qty}" style="width:50px; padding: 4px;">
                            <button class="btn btn-outline-secondary btn-increase" title="Tambah">+</button>
                        </div>

                        <input type="text" class="form-control form-control-sm note flex-grow-1" placeholder="Catatan" value="${escapeHtml(item.note || '')}" style="min-width: 120px;">

                        <button class="btn btn-sm btn-outline-danger btn-remove" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                    </div>

                    
                    <div class="d-flex gap-2 align-items-center flex-wrap">
                        <div class="flex-grow-1">
                            <div class="input-group input-group-sm rounded" style="overflow: hidden;">
                                <input type="number" class="form-control form-control-sm item-discount" placeholder="Diskon" min="0" value="${itemDiscVal}" style="padding: 4px;">
                                <select class="form-select form-select-sm item-discount-type" style="max-width: 60px; padding: 4px;">
                                    <option value="nominal" ${itemDiscType === 'nominal' ? 'selected' : ''}>Rp</option>
                                    <option value="percent" ${itemDiscType === 'percent' ? 'selected' : ''}>%</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </li>
                <hr class="my-2 opacity-50">`;
            }
        }

        cartList.innerHTML = html;
        originalTotalElem.innerText = 'Rp ' + totals.totalOriginal.toLocaleString('id-ID');
        cartTotalElem.innerText = 'Rp ' + totals.totalFinal.toLocaleString('id-ID');

        attachCartEvents();
    }

    function attachCartEvents(){
        // Remove
        cartList.querySelectorAll('.btn-remove').forEach(btn => {
            btn.onclick = function(){
                const id = btn.closest('li').dataset.id;
                // Konfirmasi hapus dengan SweetAlert
                Swal.fire({
                    title: 'Hapus item?',
                    text: 'Item akan dihapus dari keranjang',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal'
                }).then(result => {
                    if(!result.isConfirmed) return;
                    fetch("<?php echo e(url('transaksi/remove')); ?>/" + id, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(json => {
                        if(json.success){
                            cart = json.cart;
                            renderCart();
                            Swal.fire({ icon: 'success', title: 'Item dihapus', timer: 1000, showConfirmButton: false });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Gagal menghapus item' });
                        }
                    })
                    .catch(() => Swal.fire({ icon: 'error', title: 'Gagal menghubungi server' }));
                });
            };
        });

        // Increase: batasi sesuai stok
        cartList.querySelectorAll('.btn-increase').forEach(btn => {
            btn.onclick = function(){
                const li = btn.closest('li');
                const id = li.dataset.id;
                const itemEl = document.querySelector('.menu-item[data-id="'+id+'"]');
                const hasStock = itemEl ? (itemEl.dataset.hasStock === '1') : false;
                const stock = itemEl ? (Number(itemEl.dataset.stock) || 0) : 0;
                const currentQty = Number(cart[id].quantity || 0);
                if (hasStock && stock > 0 && currentQty >= stock) {
                    Swal.fire({ icon: 'warning', title: 'Melebihi stok', text: 'Jumlah sudah maksimal.' });
                    return;
                }
                cart[id].quantity = currentQty + 1;
                persistCartToSession({ action:'update', id, quantity: cart[id].quantity, note: cart[id].note || '', discount: cart[id].discount || 0, discount_type: cart[id].discount_type || 'nominal' });
                renderCart();
            };
        });

        // Decrease
        cartList.querySelectorAll('.btn-decrease').forEach(btn => {
            btn.onclick = function(){
                const li = btn.closest('li');
                const id = li.dataset.id;
                if((Number(cart[id].quantity)||1) > 1){
                    cart[id].quantity = Number(cart[id].quantity) - 1;
                    persistCartToSession({ action:'update', id, quantity: cart[id].quantity, note: cart[id].note || '', discount: cart[id].discount || 0, discount_type: cart[id].discount_type || 'nominal' });
                    renderCart();
                }
            };
        });

        // Quantity change: clamp ke stok maksimum
        cartList.querySelectorAll('.quantity').forEach(input => {
            input.onchange = function(){
                const li = input.closest('li');
                const id = li.dataset.id;
                let newQty = Math.max(1, parseInt(input.value) || 1);
                const itemEl = document.querySelector('.menu-item[data-id="'+id+'"]');
                const hasStock = itemEl ? (itemEl.dataset.hasStock === '1') : false;
                const stock = itemEl ? (Number(itemEl.dataset.stock) || 0) : 0;
                if (hasStock && stock > 0 && newQty > stock) {
                    newQty = stock;
                    Swal.fire({ icon: 'warning', title: 'Melebihi stok', text: 'Disetel ke jumlah maksimal.' });
                }
                cart[id].quantity = newQty;
                persistCartToSession({ action:'update', id, quantity: cart[id].quantity, note: cart[id].note || '', discount: cart[id].discount || 0, discount_type: cart[id].discount_type || 'nominal' });
                renderCart();
            };
        });

        // Note blur -> persist per-item note
        cartList.querySelectorAll('.note').forEach(input => {
            input.onblur = function(){
                const li = input.closest('li');
                const id = li.dataset.id;
                cart[id].note = input.value;
                persistCartToSession({ action:'update', id, quantity: cart[id].quantity, note: cart[id].note, discount: cart[id].discount || 0, discount_type: cart[id].discount_type || 'nominal' });
            };
        });

        // Item discount change (nominal)
        cartList.querySelectorAll('.item-discount').forEach(input => {
            input.onchange = function(){
                const li = input.closest('li');
                const id = li.dataset.id;
                cart[id].discount = Math.max(0, Number(input.value) || 0);
                // keep discount_type if already set
                cart[id].discount_type = cart[id].discount_type || 'nominal';
                persistCartToSession({ action:'update', id, quantity: cart[id].quantity, note: cart[id].note || '', discount: cart[id].discount, discount_type: cart[id].discount_type });
                renderCart();
            };
        });

        // Item discount type change (percent / nominal)
        cartList.querySelectorAll('.item-discount-type').forEach(sel => {
            sel.onchange = function(){
                const li = sel.closest('li');
                const id = li.dataset.id;
                cart[id].discount_type = sel.value || 'nominal';
                // ensure numeric discount exists
                cart[id].discount = Number(li.querySelector('.item-discount').value) || 0;
                persistCartToSession({ action:'update', id, quantity: cart[id].quantity, note: cart[id].note || '', discount: cart[id].discount, discount_type: cart[id].discount_type });
                renderCart();
            };
        });
    }

    // URLs and CSRF
    const addUrlBase = "<?php echo e(url('transaksi/add')); ?>";
    const updateCartUrl = "<?php echo e(route('transaksi.updateCart')); ?>";
    const saveOpenBillUrl = "<?php echo e(route('transaksi.openBill.store')); ?>";
    const openBillListUrl = "<?php echo e(route('transaksi.openBill.list')); ?>";
    const openBillLoadUrlBase = "<?php echo e(url('transaksi/open-bill/load')); ?>";
    const openBillDeleteUrlBase = "<?php echo e(url('transaksi/open-bill/delete')); ?>";
    const checkoutUrl = "<?php echo e(route('transaksi.checkout')); ?>";
    const csrf = '<?php echo e(csrf_token()); ?>';

    // Add item click (menu-card)
    document.querySelectorAll('.menu-card').forEach(card => {
        card.onclick = function(){
            // Prevent adding if out of stock (has badge Habis)
            if (card.classList.contains('opacity-75')) {
                Swal.fire({ icon: 'warning', title: 'Stok habis', text: 'Menu ini sedang tidak tersedia.' });
                return;
            }
            const itemEl = card.closest('.menu-item');
            const id = itemEl.dataset.id;
            // Client-side stock limit check
            const hasStock = itemEl.dataset.hasStock === '1';
            const stock = Number(itemEl.dataset.stock) || 0;
            const currentQty = cart[id] ? Number(cart[id].quantity || 0) : 0;
            if (hasStock && stock > 0 && currentQty >= stock) {
                Swal.fire({ icon: 'warning', title: 'Melebihi stok', text: 'Jumlah sudah maksimal.' });
                return;
            }
            fetch(addUrlBase + '/' + id, {
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            }).then(res => res.json()).then(json => {
                if(json.success){
                    if(json.cart){
                        cart = json.cart;
                    }
                    renderCart();
                }
            }).catch(()=> {
                const name = card.querySelector('.card-title').innerText.trim();
                const price = Number(card.querySelector('p').innerText.replace(/[^0-9]/g,'')) || 0;
                if(cart[id]) cart[id].quantity = Number(cart[id].quantity) + 1;
                else cart[id] = { name, price, quantity: 1, note:'', discount:0, discount_type:'nominal' };
                persistCartToSession({ action:'sync', cart });
                renderCart();
            });
        };
    });

    // Persist changes to server session
    function persistCartToSession(payload){
        const body = {};
        if(payload.action === 'sync'){
            // ensure each item has discount & discount_type fields
            const safeCart = {};
            for(const k in payload.cart){
                const it = payload.cart[k];
                safeCart[k] = {
                    name: it.name || '',
                    price: Number(it.price) || 0,
                    quantity: Number(it.quantity) || 1,
                    note: it.note || '',
                    discount: Number(it.discount || 0),
                    discount_type: it.discount_type || 'nominal'
                };
            }
            body.cart = safeCart;
            body.action = 'sync'; // Kirim action flag
        } else if(payload.action === 'remove'){
            body.cart = cart;
        } else if(payload.action === 'update'){
            body.id = payload.id;
            body.quantity = payload.quantity;
            body.note = payload.note || '';
            body.discount = payload.discount || 0;
            body.discount_type = payload.discount_type || 'nominal';
        }

        // also send global info
body.customer_name = customerNameInput.value || '';
body.order_type = orderTypeSelect.value || 'dine_in';

// hanya kirim diskon global kalau payload.id === null (berarti bukan item)
if (payload.id === null) {
    body.discount_value = Number(discountValue.value) || 0;
    body.discount_type = discountType.value || 'nominal'; // default lebih aman ke nominal
}


        fetch(updateCartUrl, {
            method: 'POST',
            headers:{
                'X-CSRF-TOKEN': csrf,
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify(body)
        }).then(r => r.json())
          .then(json => {
              if(json.success && json.cart !== undefined){
                  cart = json.cart;
                  renderCart();
              }
          }).catch(()=>{ /* ignore network error */ });
    }

    // Apply / remove global discount
    applyDiscountBtn.onclick = function(){
        const v = Number(discountValue.value) || 0;
        const isGiving = v > 0;
        Swal.fire({
            title: isGiving ? 'Berikan diskon?' : 'Batalkan diskon?',
            text: isGiving ? 'Total akan dikurangi sesuai diskon.' : 'Total akan kembali normal.',
            icon: isGiving ? 'question' : 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#0d6efd'
        }).then(result => {
            if(result.isConfirmed){
                persistCartToSession({ action:'update', id: null, quantity: null, note:'', discount:0, discount_type:'nominal' });
                renderCart();
            }
        });
    };

    // Clear Cart Button
    document.getElementById('clearCartBtn').onclick = function(){
        if(Object.keys(cart).length === 0){
            Swal.fire({ icon: 'info', title: 'Keranjang sudah kosong', confirmButtonColor: '#0d6efd' });
            return;
        }
        Swal.fire({
            title: 'Kosongkan keranjang?',
            text: 'Semua item akan dihapus',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, kosongkan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#dc3545'
        }).then(result => {
            if(result.isConfirmed){
                cart = {};
                discountValue.value = 0;
                discountType.value = 'percent';
                customerNameInput.value = '';
                orderTypeSelect.value = 'dine_in';

                // Fetch ke server untuk clear cart di session
                fetch(updateCartUrl, {
                    method: 'POST',
                    headers:{
                        'X-CSRF-TOKEN': csrf,
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        cart: {},
                        action: 'sync',
                        customer_name: '',
                        order_type: 'dine_in'
                    })
                }).then(r => r.json())
                  .then(json => {
                      cart = json.cart || {};
                      renderCart();
                      Swal.fire({ icon: 'success', title: 'Keranjang dikosongkan', timer: 1000, showConfirmButton: false });
                  })
                  .catch(() => {
                      cart = {};
                      renderCart();
                      Swal.fire({ icon: 'success', title: 'Keranjang dikosongkan', timer: 1000, showConfirmButton: false });
                  });
            }
        });
    };

    // Quick pay buttons render
   function renderQuickPayButtons(){
    quickPayButtons.innerHTML = '';
    const totals = calculateTotalsLocal();
    const total = totals.totalFinal;

    // Pilihan nominal relevan
    const roundUpTo = 5000;  // kelipatan 5 ribu
    const base = Math.ceil(total / roundUpTo) * roundUpTo;

    // Tombol utama: total asli, kelipatan, kelipatan+1
    const options = [
        total,             // total asli
        base,              // dibulatkan ke atas kelipatan 5rb
        base + roundUpTo   // kelipatan berikutnya
    ];

    // Tambahkan opsi nominal besar (50rb, 100rb)
    const bigOptions = [50000, 100000];
    bigOptions.forEach(v => {
        if(v > total) options.push(v);
    });

    // Hapus duplikat
    const uniqueOptions = [...new Set(options)];

    // Render tombol
    uniqueOptions.forEach(val => {
        const b = document.createElement('button');
        b.type = 'button';
        b.className = 'btn btn-outline-primary btn-sm';
        b.innerText = val.toLocaleString('id-ID');
        b.onclick = () => {
            manualPay.value = val;
            updateChange();
        };
        quickPayButtons.appendChild(b);
    });
}



    function updateChange(){
        const paid = Number(manualPay.value) || 0;
        const totals = calculateTotalsLocal();
        const change = paid - totals.totalFinal;
        paymentChangeElem.innerText = 'Rp ' + (change > 0 ? change : 0).toLocaleString('id-ID');
    }
    manualPay.oninput = updateChange;

    proceedPaymentBtn.onclick = function(){
    if(Object.keys(cart).length === 0){
        Swal.fire({ icon: 'warning', title: 'Keranjang kosong' });
        return;
    }
    renderQuickPayButtons(); // render tombol cepat baru
    const totals = calculateTotalsLocal();
    paymentTotalElem.innerText = 'Rp ' + totals.totalFinal.toLocaleString('id-ID');
    manualPay.value = '';
    paymentChangeElem.innerText = 'Rp 0';
    paymentModal.show();
};


    // Confirm payment -> checkout
confirmPaymentBtn.onclick = function(){
    const method = document.getElementById('paymentMethod').value;
    const totals = calculateTotalsLocal();
    let paid = 0;

    if (method === 'cash') {
        paid = Number(manualPay.value) || 0;
        if (paid < totals.totalFinal) {
            Swal.fire({ icon: 'error', title: 'Uang bayar kurang', text: 'Silakan periksa nominal.' });
            return;
        }
    } else {
        // Non tunai → langsung set paid = totalFinal
        paid = totals.totalFinal;
    }

    // ✅ disable button sementara untuk cegah klik ganda
    confirmPaymentBtn.disabled = true;

    fetch(checkoutUrl, {
        method: 'POST',
        headers:{
            'X-CSRF-TOKEN': csrf,
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            cart: cart,
            total: totals.totalFinal,
            customer_name: customerNameInput.value || '',
            order_type: orderTypeSelect.value || 'dine_in',
            paid: paid,
            payment_method: method, // ✅ pastikan dikirim
            discount_value: Number(discountValue.value) || 0,
            discount_type: discountType.value || 'percent'
        })
    })
    .then(r => r.json())
    .then(json => {
        if(json.success){
            // build struk dari client cart
            let html = `
<div id="receiptArea">
    <div style="text-align:center">
        <strong>STRUK PEMBAYARAN</strong><br>
        ${new Date().toLocaleString('id-ID')}
        <hr>
    </div>
    <p>Customer: ${escapeHtml(customerNameInput.value || '-')}<br>
    Jenis: ${orderTypeSelect.value === 'dine_in' ? 'Dine In' : 'Take Away'}</p>
    <table style="width:100%;font-size:11px">
        <thead>
            <tr>
                <th style="text-align:left">Menu</th>
                <th style="text-align:center">Qty</th>
                <th style="text-align:right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
`;
            Object.values(cart).forEach(i => {
                const price = Number(i.price);
                const qty = Number(i.quantity);
                const subtotal = price * qty;
                const discVal = Number(i.discount || 0);
                const discType = i.discount_type || 'nominal';
                let discAmount = 0;
                if (discVal > 0) {
                    if (discType === 'percent') discAmount = subtotal * discVal / 100;
                    else discAmount = discVal;
                }
                const final = Math.max(0, subtotal - discAmount);

                html += `
<tr>
  <td>${escapeHtml(i.name)}${i.note ? `<br><small>(${escapeHtml(i.note)})</small>` : ''}${discAmount > 0 ? `<br><small>Diskon: ${discType === 'percent' ? discVal+'%' : 'Rp '+discAmount.toLocaleString('id-ID')}</small>` : ''}</td>
  <td style="text-align:center">${qty}</td>
  <td style="text-align:right">Rp ${final.toLocaleString('id-ID')}</td>
</tr>
`;
            });

            const discVal = Number(discountValue.value) || 0;
            if(discVal > 0){
                if(discountType.value === 'percent'){
                    html += `<tr><td colspan="3">Diskon global: ${discVal}%</td></tr>`;
                } else {
                    html += `<tr><td colspan="3">Diskon global: Rp ${discVal.toLocaleString('id-ID')}</td></tr>`;
                }
            }

            html += `
        </tbody>
    </table>
    <hr>
    <p>Total: Rp ${totals.totalFinal.toLocaleString('id-ID')}</p>
    <p>Bayar: Rp ${paid.toLocaleString('id-ID')}</p>
    <p>Kembali: Rp ${(paid - totals.totalFinal).toLocaleString('id-ID')}</p>
    <div style="text-align:center;margin-top:10px">
        Terima Kasih 🙏
    </div>
</div>
`;

            transactionDetailBody.innerHTML = html;
            transactionDetailModal.show();

            // clear cart client
            cart = {};
            renderCart();
            paymentModal.hide();
        } else {
            Swal.fire({ icon: 'error', title: 'Pembayaran gagal', text: json.message || 'Terjadi kesalahan' });
        }
    })
    .catch(()=> Swal.fire({ icon: 'error', title: 'Gagal menghubungi server' }))
    .finally(() => {
        confirmPaymentBtn.disabled = false; // enable button lagi
    });
};


    // Save Open Bill
    saveOpenBillBtn.onclick = function(){
        if(Object.keys(cart).length === 0){
            Swal.fire({ icon: 'warning', title: 'Keranjang kosong' });
            return;
        }
        fetch(saveOpenBillUrl, {
            method: 'POST',
            headers:{
                'X-CSRF-TOKEN': csrf,
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                cart: cart,
                customer_name: customerNameInput.value || '',
                order_type: orderTypeSelect.value || 'dine_in',
                discount_value: Number(discountValue.value) || 0,
                discount_type: discountType.value || 'percent'
            })
        }).then(r => r.json()).then(json => {
            if(json.success){
                Swal.fire({ icon: 'success', title: 'Transaksi tersimpan', timer: 1200, showConfirmButton: false });
                cart = {};
                renderCart();
            } else {
                Swal.fire({ icon: 'error', title: 'Gagal menyimpan', text: json.message || 'Coba lagi nanti.' });
            }
        }).catch(()=> Swal.fire({ icon: 'error', title: 'Gagal menghubungi server' }));
    };

    // Open bill list
    openBillListBtn.onclick = function(){
        fetch(openBillListUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept':'application/json' } })
        .then(r => r.json())
        .then(data => {
            let html = '';
            if(!data || data.length === 0){
                html = '<tr><td colspan="3" class="text-center">Belum ada transaksi tersimpan</td></tr>';
            } else {
                data.forEach(bill => {
                    html += `<tr>
                        <td>${escapeHtml(bill.customer_name || '-')}</td>
                        <td>Rp ${Number(bill.total || 0).toLocaleString('id-ID')}</td>
                        <td>
                            <button class="btn btn-sm btn-success btn-load-bill" data-id="${escapeHtml(bill.id)}"><i class="fa-solid fa-arrow-right"></i> Buka</button>
                            <button class="btn btn-sm btn-danger btn-delete-bill" data-id="${escapeHtml(bill.id)}"><i class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>`;
                });
            }
            openBillTableBody.innerHTML = html;

            // load
            openBillTableBody.querySelectorAll('.btn-load-bill').forEach(btn => {
                btn.onclick = function(){
                    const id = btn.dataset.id;
                    fetch(openBillLoadUrlBase + '/' + id, { headers: { 'X-Requested-With':'XMLHttpRequest','Accept':'application/json' } })
                    .then(r => r.json()).then(json => {
                        if(json.success && json.bill){
                            cart = json.bill.cart || {};
                            customerNameInput.value = json.bill.customer_name || '';
                            orderTypeSelect.value = json.bill.order_type || 'dine_in';
                            discountValue.value = json.bill.discount_value || 0;
                            discountType.value = json.bill.discount_type || 'percent';
                            renderCart();
                            openBillModal.hide();
                            Swal.fire({ icon: 'success', title: 'Transaksi dimuat', timer: 1000, showConfirmButton: false });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Gagal memuat transaksi', text: json.message || 'Coba lagi nanti.' });
                        }
                    }).catch(()=> Swal.fire({ icon: 'error', title: 'Gagal menghubungi server' }));
                };
            });

            openBillTableBody.querySelectorAll('.btn-delete-bill').forEach(btn => {
                btn.onclick = function(){
                    const id = btn.dataset.id;
                    Swal.fire({
                        title: 'Hapus transaksi tersimpan?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus',
                        cancelButtonText: 'Batal'
                    }).then(result => {
                        if(!result.isConfirmed) return;
                        fetch(openBillDeleteUrlBase + '/' + id, {
                            method:'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrf,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept':'application/json'
                            }
                        }).then(r => r.json()).then(json => {
                            if(json.success){
                                btn.closest('tr').remove();
                                Swal.fire({ icon: 'success', title: 'Dihapus', timer: 800, showConfirmButton: false });
                            } else {
                                Swal.fire({ icon: 'error', title: 'Gagal menghapus', text: json.message || 'Coba lagi nanti.' });
                            }
                        }).catch(()=> Swal.fire({ icon: 'error', title: 'Gagal menghubungi server' }));
                    });
                };
            });

            openBillModal.show();
        }).catch(()=> {
            openBillTableBody.innerHTML = '<tr><td colspan="3" class="text-center">Gagal memuat data</td></tr>';
            openBillModal.show();
            Swal.fire({ icon: 'error', title: 'Gagal memuat Open Bill' });
        });
    };

    // Search & filter
    let currentCategory = '';
    function filterMenu() {
        const search = (searchMenuInput.value || '').toLowerCase();
        const category = currentCategory.toLowerCase();
        menuList.querySelectorAll('.menu-item').forEach(item => {
            const name = (item.dataset.name || '').toLowerCase();
            const cat = (item.dataset.category || '').toLowerCase();
            const matchName = name.includes(search);
            const matchCat = !category || cat === category;
            item.style.display = (matchName && matchCat) ? '' : 'none';
        });
    }
    searchMenuInput.addEventListener('input', filterMenu);

    // Category filter dropdown
    document.querySelectorAll('.category-filter').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            currentCategory = this.dataset.value || '';
            filterMenu();
        });
    });

    // initial render
    renderCart();
});
</script>

<!-- Modern Toast Notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080">
  <div id="mainToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body" id="mainToastBody">
        <!-- Pesan notifikasi -->
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>

<script>
function showToast(message, type = 'success') {
    const toast = document.getElementById('mainToast');
    const toastBody = document.getElementById('mainToastBody');
    toastBody.innerText = message;
    toast.classList.remove('bg-success', 'bg-danger', 'bg-warning', 'bg-info');
    toast.classList.add('bg-' + type);
    const bsToast = bootstrap.Toast.getOrCreateInstance(toast);
    bsToast.show();
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projek_pos\qassir_na\resources\views/transactions/index.blade.php ENDPATH**/ ?>