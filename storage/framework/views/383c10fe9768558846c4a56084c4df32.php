<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Qassirin - <?php echo $__env->yieldContent('title'); ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f7fb;
            overflow-x: hidden;
        }

        /* ========================
           SIDEBAR
        ==========================*/
        .sidebar {
            height: 100vh;
            position: fixed;
            left: 0; top: 0;
            width: 240px;
            background: #ffffff;
            border-right: 1px solid #dbe1ea;
            box-shadow: 1px 0 8px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: 0.3s;
            overflow: hidden;
        }
        .sidebar.hidden { transform: translateX(-100%); }

        /* Scrollable Nav */
        .sidebar .nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding-bottom: 20px;
        }
        .sidebar .nav::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar .nav::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar .nav::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 10px;
        }
        .sidebar .nav::-webkit-scrollbar-thumb:hover {
            background: #a0aec0;
        }

        /* Brand */
        .sidebar .brand {
            padding: 18px 20px;
            border-bottom: 1px solid #e1e7ef;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sidebar .brand img {
            height: 36px;
            width: 36px;
            object-fit: contain;
        }
        .sidebar .brand h4 {
            font-size: 19px;
            margin: 0;
            font-weight: 700;
            color: #007bff;
            letter-spacing: 0.5px;
        }

        /* Nav */
        .nav-link {
            color: #6c7a91;
            font-weight: 500;
            padding: 12px 20px;
            border-radius: 6px;
            margin: 4px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 15px;
            transition: 0.2s;
        }
        .nav-link i {
            font-size: 18px;
            margin-right: 14px;
            width: 20px;
            text-align: center;
        }
        .nav-link:hover {
            background: #e7f1ff;
            color: #007bff;
        }
        .nav-link:hover i { color: #007bff; }

        .nav-link.active {
            background: #d9eaff;
            color: #007bff;
            font-weight: 600;
        }
        .nav-link.active i { color: #007bff; }

        /* Submenu */
        .submenu .nav-link {
            padding: 8px 40px;
            font-size: 14px;
        }

        /* ==========================
           TOPBAR
        ==========================*/
        .topbar {
            height: 60px;
            background: #ffffff;
            border-bottom: 1px solid #dbe1ea;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            position: sticky;
            top: 0;
            z-index: 1100;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        }
        .topbar h5 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #007bff;
        }
        .topbar .left {
            display: flex; align-items: center; gap: 12px;
        }
        .toggle-btn {
            font-size: 22px;
            background: none;
            border: none;
            color: #6c7a91;
            cursor: pointer;
        }
        .user-role {
            font-weight: 500;
            color: #6c7a91;
            font-size: 14px;
        }

        /* ==========================
           MAIN CONTENT
        ==========================*/
        .content {
            margin-left: 240px;
            padding: 25px;
            transition: 0.3s;
        }
        .content.full { margin-left: 0; }

        /* ==========================
           PRINT MODE
        ==========================*/
        @media print {
            .sidebar, .topbar { display: none !important; }
            .content {
                margin: 0 !important;
                padding: 0 !important;
                width: 58mm;
            }
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">
        <div class="brand">
            <img src="<?php echo e(asset('images/qassirin_logo.png')); ?>" alt="logo">
            <h4>Qassirin</h4>
        </div>

        <nav class="nav flex-column">

            <?php if(auth()->guard()->check()): ?>

            <a class="nav-link <?php echo e(request()->is('dashboard') ? 'active' : ''); ?>"
               href="<?php echo e(route('dashboard.index')); ?>">
                <span><i class="fa-solid fa-house"></i> Dashboard</span>
            </a>

            <a class="nav-link <?php echo e(request()->is('transaksi*') ? 'active' : ''); ?>"
               href="<?php echo e(route('transaksi.index')); ?>">
               <span><i class="fa-solid fa-cash-register"></i> Transaksi</span>
            </a>

                <a class="nav-link <?php echo e(request()->is('transaksi/open-bill') ? 'active' : ''); ?>"
                    href="<?php echo e(route('transaksi.openBill.page')); ?>">
                    <span><i class="fa-solid fa-file-invoice"></i> Transaksi Tersimpan</span>
                </a>

            <a class="nav-link <?php echo e(request()->is('riwayat') ? 'active' : ''); ?>"
               href="<?php echo e(route('riwayat.index')); ?>">
               <span><i class="fa-solid fa-clock-rotate-left"></i> Riwayat</span>
            </a>

            <!-- Produk -->
            <a class="nav-link d-flex"
                data-bs-toggle="collapse" href="#produkMenu"
                aria-expanded="<?php echo e(request()->is('produk*') ? 'true' : 'false'); ?>">
                <span><i class="fa-solid fa-box"></i> Produk</span>
                <i class="fa-solid fa-chevron-down small"></i>
            </a>
            <div class="collapse submenu <?php echo e(request()->is('produk*') ? 'show' : ''); ?>" id="produkMenu">
                <a class="nav-link" href="<?php echo e(route('produk.index')); ?>">Daftar Produk</a>
            </div>




            <!-- Kas -->
            <a class="nav-link d-flex"
                data-bs-toggle="collapse" href="#kasMenu"
                aria-expanded="<?php echo e(request()->is('kas*') ? 'true' : 'false'); ?>">
                <span><i class="fa-solid fa-vault"></i> Rekap Kas</span>
                <i class="fa-solid fa-chevron-down small"></i>
            </a>
            <div class="collapse submenu <?php echo e(request()->is('kas*') ? 'show' : ''); ?>" id="kasMenu">
                <a class="nav-link" href="<?php echo e(route('kas.opening.form')); ?>">Buka Kasir</a>
                <a class="nav-link" href="<?php echo e(route('kas.closing.form')); ?>">Tutup Kasir</a>
                <a class="nav-link" href="<?php echo e(route('kas.history')); ?>">Riwayat Closing</a>
            </div>

            <!-- Owner Only -->
            <?php if(Auth::user()->role === 'owner'): ?>
            <a class="nav-link d-flex"
                data-bs-toggle="collapse" href="#karyawanMenu"
                aria-expanded="<?php echo e(request()->is('karyawan*') ? 'true' : 'false'); ?>">
                <span><i class="fa-solid fa-users"></i> Kelola Karyawan</span>
                <i class="fa-solid fa-chevron-down small"></i>
            </a>
            <div class="collapse submenu <?php echo e(request()->is('karyawan*') ? 'show' : ''); ?>" id="karyawanMenu">
                <a class="nav-link" href="<?php echo e(route('karyawan.index')); ?>">Daftar Karyawan</a>
                <a class="nav-link" href="<?php echo e(route('karyawan.create')); ?>">Tambah Karyawan</a>
            </div>

            <a class="nav-link <?php echo e(request()->is('laporan/penjualan') ? 'active' : ''); ?>" href="<?php echo e(route('laporan.penjualan')); ?>">
                <span><i class="fa-solid fa-chart-line"></i> Laporan Penjualan</span>
            </a>
            <?php endif; ?>

            <?php endif; ?>

        </nav>
    </div>

    <!-- TOPBAR -->
    <div class="topbar">
        <div class="left">
            <button id="toggleSidebar" class="toggle-btn"><i class="fa-solid fa-bars"></i></button>

            <img src="<?php echo e(asset('images/qassirin_logo.png')); ?>" style="height:30px;">
            <h5>Qassirin</h5>
        </div>

        <div class="right d-flex align-items-center gap-3">
            <?php if(auth()->guard()->check()): ?>
                <span class="user-role">
                    <i class="fa-solid fa-user"></i>
                    <?php echo e(Auth::user()->name); ?> (<?php echo e(Auth::user()->role === 'owner' ? 'Owner' : 'Kasir'); ?>)
                </span>

                <form action="<?php echo e(route('logout')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn-sm btn-outline-danger">Logout</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="content" id="content">
        <?php echo $__env->yieldContent('content'); ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');
        const toggleBtn = document.getElementById('toggleSidebar');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('hidden');
            content.classList.toggle('full');
        });
    </script>
</body>
</html>
<?php /**PATH D:\projek_pos\qassir_na\resources\views/layouts/app.blade.php ENDPATH**/ ?>