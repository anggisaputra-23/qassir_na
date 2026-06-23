# Qassir NA — POS System

Sistem Point of Sale untuk kafe/restoran berbasis **Laravel 12**.

## Fitur

- Manajemen produk (makanan & minuman)
- Transaksi POS dengan cart berbasis session
- Diskon per-item & global (nominal/persen)
- Open Bills (draft transaksi)
- Cetak receipt thermal (58mm)
- Manajemen shift kasir (buka/tutup kas)
- Pengeluaran operasional per shift
- Laporan penjualan owner
- Manajemen karyawan
- Dashboard analitik (Chart.js)

## Requirements

- PHP 8.2+
- MySQL (atau SQLite untuk development)
- Composer
- Node.js & NPM

## Instalasi

```bash
composer install
npm install
cp .env.example .env
# sesuaikan konfigurasi database di .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link
```

## Akun Demo

| Role | Email | Password |
|------|-------|----------|
| Owner | demo@owner.com | password |
| Kasir | demo@kasir.com | password |

## Menjalankan Aplikasi

```bash
composer run dev
```

Atau secara terpisah:

```bash
php artisan serve
npm run dev
```

## Testing

```bash
composer test
```

## Teknologi

Laravel 12, Bootstrap 5.3, Tailwind CSS 4, Alpine.js, Chart.js, Font Awesome 6, Vite, Pest PHP
