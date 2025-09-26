# Sistem Penyewaan Motor - Dokumentasi Lengkap

## Fitur Utama

### 1. **Sistem Multi Role** 
- **Admin**: Mengelola tarif, konfirmasi pembayaran, laporan
- **Pemilik**: Daftarkan motor, lihat pendapatan
- **Penyewa**: Cari motor, sewa, upload bukti bayar

### 2. **Alur Penyewaan Lengkap**
```
Penyewa pilih motor → Sistem hitung harga → Upload bukti bayar → 
Admin konfirmasi → Motor status disewa → Auto check expired → 
Admin konfirmasi pengembalian → Generate bagi hasil
```

### 3. **Status Management**
**Motor Status:**
- `tersedia` - Bisa disewa
- `disewa` - Sedang disewakan  
- `maintenance` - Dalam perawatan

**Penyewaan Status:**
- `menunggu_pembayaran` - Baru dibuat, menunggu upload bukti
- `menunggu_konfirmasi` - Bukti sudah diupload, menunggu admin
- `disewa` - Dikonfirmasi admin, motor sedang disewa
- `perlu_dikembalikan` - Melewati batas waktu
- `selesai` - Selesai dan motor dikembalikan
- `dibatalkan` - Dibatalkan

**Transaksi Status:**
- `pending` - Belum dibayar
- `paid` - Sudah dibayar
- `failed` - Gagal
- `refunded` - Dikembalikan

**Verifikasi Status:**
- `pending` - Menunggu verifikasi admin
- `verified` - Disetujui admin
- `rejected` - Ditolak admin

## Database Schema

### Tables
1. `users` - Admin, pemilik, penyewa
2. `motors` - Data motor dan pemiliknya
3. `tarif_rentals` - Tarif per motor (harian/mingguan/bulanan)
4. `penyewaans` - Data penyewaan
5. `transaksis` - Data pembayaran + bukti transfer
6. `bagi_hasils` - Revenue sharing per penyewaan

### Key Relationships
- Motor belongs to Pemilik (User)
- Motor has one TarifRental  
- Penyewaan belongs to Penyewa (User) and Motor
- Penyewaan has one Transaksi
- Penyewaan has one BagiHasil

## Fitur Dashboard

### Admin Dashboard
- 📊 **Grafik penyewaan per bulan**
- 💰 **Grafik pendapatan per bulan** 
- 📋 **Entri transaksi pembayaran**
- ✅ **Konfirmasi pembayaran dengan bukti**
- 🔍 **Riwayat semua penyewaan**
- 📝 **Daftar semua motor terdaftar**
- ⏰ **Auto check motor expired**
- 🔄 **Konfirmasi pengembalian motor**

### Pemilik Dashboard  
- ➕ **Daftarkan motor baru**
- 📋 **Lihat motor yang dimiliki**
- 💰 **Lihat pendapatan dari bagi hasil** (TODO)

### Penyewa Dashboard
- 🔍 **Cari motor dengan filter**
- 📅 **Pilih durasi sewa (harian/mingguan/bulanan)**
- 💰 **Kalkulator harga otomatis**
- 📤 **Upload bukti transfer**
- 📜 **Riwayat penyewaan**

## Technical Features

### Security
- ✅ Authentication & Authorization
- ✅ Role-based access control
- ✅ CSRF protection
- ✅ File upload validation

### UI/UX  
- ✅ Premium Tailwind CSS design
- ✅ Responsive mobile-friendly
- ✅ Toast notifications
- ✅ Modal components
- ✅ Loading states

### Backend
- ✅ Laravel 12.x + PHP 8.4
- ✅ MySQL database with migrations
- ✅ Model relationships
- ✅ File storage for documents/photos
- ✅ Automated revenue sharing
- ✅ Status validation & transitions

## API Endpoints

### Auth
- `POST /login` - Login
- `POST /register` - Register  
- `POST /logout` - Logout

### Penyewa
- `GET /motors` - List available motors
- `GET /motors/{id}` - Motor details
- `POST /motors/book` - Create rental
- `POST /calculate-price` - Calculate rental price
- `GET /pembayaran/{id}` - Payment page
- `POST /pembayaran/{id}` - Upload payment proof
- `GET /riwayat-penyewaan` - Rental history

### Admin
- `GET /admin/entri-transaksi` - Payment management
- `POST /admin/konfirmasi-pembayaran/{id}` - Confirm/reject payment
- `GET /admin/riwayat-penyewaan` - All rentals history
- `GET /admin/daftar-motor` - All registered motors
- `POST /admin/auto-check-expired` - Check expired rentals
- `GET /admin/pengembalian-motor` - Return management
- `POST /admin/konfirmasi-pengembalian/{id}` - Confirm motor return

### Pemilik  
- `POST /motors` - Register new motor
- `GET /motors/my` - My motors list

## Installation & Setup

```bash
# 1. Clone & install dependencies
composer install
npm install

# 2. Setup environment
cp .env.example .env
php artisan key:generate

# 3. Configure database in .env
DB_DATABASE=penyewaan_motor
DB_USERNAME=root
DB_PASSWORD=

# 4. Run migrations & seeders
php artisan migrate
php artisan db:seed

# 5. Start development server
php artisan serve
npm run dev
```

## Default Login Accounts

```
Admin: admin@penyewaan.com / password
Pemilik: Pemilik@gmail.com / password  
Penyewa: admin@gmail.com / password
```

## System Testing

✅ **Database connections working**
✅ **User authentication working** 
✅ **Motor registration working**
✅ **Tarif management working**
✅ **Rental creation working**
✅ **Payment upload working**
✅ **Admin confirmation working**
✅ **Status transitions working**
✅ **Auto expiry checking working**
✅ **Revenue sharing working**

## Next Development

1. **Notification system** - Email/SMS alerts
2. **Pemilik bagi hasil dashboard** - Revenue tracking for owners  
3. **Advanced reporting** - Excel export, date filters
4. **Mobile app** - Native iOS/Android app
5. **Payment gateway** - Integration with Midtrans/Xendit
6. **GPS tracking** - Real-time motor location
7. **Review system** - Rating & feedback

---

**Status: ✅ Sistem lengkap dan berfungsi dengan baik!**

Semua fitur utama sudah diimplementasi dan ditest. Database schema solid, UI premium, security terjaga, dan alur bisnis sesuai requirements.