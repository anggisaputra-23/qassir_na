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

## Deploy ke Render.com (Gratis)

[![Deploy to Render](https://render.com/images/deploy-to-render-button.svg)](https://render.com/deploy)

1. Fork/push repo ini ke GitHub
2. Buka [Render.com](https://render.com) → **New** → **Web Service**
3. Hubungkan GitHub repo Anda
4. Pilih **Runtime** → **Docker**
5. Render otomatis mendeteksi `Dockerfile`
6. Set **Start Command** biarkan kosong (gunakan CMD dari Dockerfile)
7. Tambahkan **Environment Variables**:
   - `APP_ENV` → `production`
   - `APP_DEBUG` → `false`
   - `DB_CONNECTION` → `mysql`
   - `DB_HOST` → *(host MySQL Anda)*
   - `DB_DATABASE` → `qassir_na`
   - `DB_USERNAME` → *(username)*
   - `DB_PASSWORD` → *(password)*
   - `APP_KEY` → *(generate dengan `php artisan key:generate --show`)*
8. Pada bagian **Deploy**, tambahkan **Post-Deploy Command**:
   ```bash
   php artisan migrate --force && php artisan db:seed --force
   ```
9. Klik **Create Web Service**
10. Deploy selesai — aplikasi live di `https://qassir-na.onrender.com`

> **💡 Database gratis:** [Render MySQL](https://render.com/docs/databases) (1GB), [Railway MySQL](https://railway.app/), [Aiven](https://aiven.io/), atau [PlanetScale](https://planetscale.com/).

### Deploy Manual dengan Docker

```bash
docker build -t qassir-na .
docker run -p 8080:8080 \
  -e APP_ENV=production \
  -e APP_DEBUG=false \
  -e APP_KEY=base64:... \
  -e DB_CONNECTION=mysql \
  -e DB_HOST=your-db-host \
  -e DB_DATABASE=qassir_na \
  -e DB_USERNAME=root \
  -e DB_PASSWORD=your-password \
  qassir-na
```

Akses di `http://localhost:8080`.

## Teknologi

Laravel 12, Bootstrap 5.3, Tailwind CSS 4, Alpine.js, Chart.js, Font Awesome 6, Vite, Pest PHP, Docker
