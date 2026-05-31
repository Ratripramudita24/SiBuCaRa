# SiBuCaRa - Sistem Penjadwalan Budidaya Cabai Rawit

Sistem manajemen budidaya cabai rawit dengan 3 role pengguna dan fitur generate jadwal otomatis.

## Konsep Sistem

### 3 Role Pengguna
1. **Owner (Pemilik Kebun)** - Membuat tanaman, membuat akun pekerja, monitoring progres
2. **Worker (Pekerja Kebun)** - Mengerjakan tugas budidaya dan update status aktivitas
3. **Penyuluh** - Memantau perkembangan budidaya semua owner dan memberikan rekomendasi

### Alur Kerja Sistem
1. Owner registrasi → login
2. Owner buat tanaman (pilih varietas, input tanggal tanam)
3. Sistem auto-generate jadwal 90 hari (10 aktivitas)
4. Owner buat akun worker
5. Worker login → lihat tugas → update status
6. Penyuluh login → monitor semua progres

## Setup Instruksi

### 1. Database Setup
```powershell
# Buat database
mysql -u root
> CREATE DATABASE sibucara;
> EXIT;
```

### 2. Environment Setup
```powershell
# Copy env file
Copy-Item -Path .env.example -Destination .env

# Edit .env
$env_file = ".env"
# Pastikan:
# APP_ENV=local
# APP_DEBUG=true
# DB_DATABASE=sibucara
# DB_USERNAME=root
# DB_PASSWORD=
```

### 3. Generate APP_KEY
```powershell
php artisan key:generate
```

### 4. Run Migrations
```powershell
php artisan migrate --seed
```

### 5. Clear Cache
```powershell
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### 6. Start Server
```powershell
php artisan serve
```

Buka: `http://localhost:8000`

## Default Demo Users (dari seeder)

| Role | Email | Password |
|------|-------|----------|
| Owner | owner@example.com | (password acak, buat sendiri via register) |
| Worker | worker@example.com | (dibuat oleh owner) |
| Penyuluh | penyuluh@example.com | (password acak, buat sendiri via register) |

## Fitur Utama

### Owner Dashboard
- Total aktivitas, aktivitas selesai, belum selesai, tidak dilakukan
- Progress bar budidaya keseluruhan
- List tanaman dengan detail dan aksi
- Management pekerja
- Buat tanaman baru → auto-generate jadwal

### Worker Dashboard
- List tugas berdasarkan role (worker)
- Update status aktivitas (belum dikerjakan, sedang dikerjakan, selesai, tidak dilakukan)
- Tambah catatan/alasan untuk setiap aktivitas
- Lihat sistem notes tentang kondisi cuaca, dll

### Penyuluh Dashboard
- Monitor semua tanaman dan progres
- Lihat statistik aktivitas keseluruhan
- Lihat aktivitas yang terlambat
- Akses ke detail setiap tanaman

## Database Schema

### Migrations Baru
1. `2026_05_30_000001_add_role_to_users_table.php` - Tambah role & created_by ke users
2. `2026_05_30_000002_create_varieties_table.php` - Buat tabel varieties
3. `2026_05_30_000003_add_owner_and_variety_to_plants_table.php` - Tambah FK ke plants
4. `2026_05_30_000004_add_assigned_user_status_to_plant_activities_table.php` - Tambah assigned_user, status, notes

### Tabel Varietas
- Cabai Rawit Hijau
- Cabai Rawit Merah
- Cabai Rawit Putih

## Status Aktivitas
- **belum_dikerjakan** - Aktivitas belum dimulai
- **sedang_dikerjakan** - Aktivitas sedang berlangsung
- **selesai** - Aktivitas sudah selesai
- **tidak_dilakukan** - Aktivitas tidak bisa dilakukan (ada alasan)

## Jadwal Auto-Generate (10 Aktivitas)
1. Seleksi Benih (Hari 0) - Owner
2. Penyemaian (Hari 0) - Worker
3. Perawatan Bibit (Hari 7) - Worker
4. Pemindahan/Transplanting (Hari 21) - Worker
5. Pemupukan I (Hari 28) - Worker
6. Penyiraman (Hari 35) - Worker
7. Pengendalian Hama (Hari 42) - Worker
8. Pemupukan II (Hari 56) - Worker
9. Persiapan Panen (Hari 70) - Worker
10. Panen (Hari 90) - Worker

## File Struktur Baru/Update

### Models
- `app/Models/User.php` - Update dengan role, relationships
- `app/Models/Variety.php` - Baru
- `app/Models/Plant.php` - Update dengan owner_id, variety_id
- `app/Models/PlantActivity.php` - Update dengan assigned_user_id, status, notes
- `app/Models/Notification.php` - Update dengan recipient_role, recipient_user_id

### Controllers
- `app/Http/Controllers/PlantController.php` - Update untuk 3 role
- `app/Http/Controllers/PlantActivityController.php` - Baru
- `app/Http/Controllers/WorkerController.php` - Baru
- `app/Http/Controllers/DashboardController.php` - Baru (optional)

### Services
- `app/Services/PlantService.php` - Update generate jadwal lengkap

### Policies
- `app/Policies/PlantPolicy.php` - Baru
- `app/Policies/PlantActivityPolicy.php` - Baru

### Routes
- `routes/web.php` - Update dengan routes untuk workers, activities

### Seeders
- `database/seeders/VarietySeeder.php` - Baru
- `database/seeders/DatabaseSeeder.php` - Update

### Migrations
- 4 migration files baru (lihat di atas)

## Testing Quick Start

### 1. Register & Login sebagai Owner
```
1. Klik "Register" di welcome page
2. Isi form: name, email, password
3. Login dengan credentials tersebut
```

### 2. Buat Tanaman
```
1. Di dashboard, klik "+ Tambah Tanaman"
2. Isi:
   - Nama: "Cabai Rawit Blok A"
   - Varietas: "Cabai Rawit Merah"
   - Tanggal Tanam: <pilih tanggal>
   - Lokasi: "Lahan Utara"
3. Submit → Sistem generate jadwal otomatis
```

### 3. Buat Worker
```
1. Di dashboard (Pekerja Saya), klik "+ Baru"
2. Isi:
   - Nama: "Budidya"
   - Email: "budidya@example.com"
   - Password: "password123"
3. Submit
```

### 4. Login sebagai Worker
```
1. Logout dari owner account
2. Register dengan email worker baru, atau login dengan email yang dibuat owner
3. Di dashboard, worker lihat tugas yang di-assign
```

### 5. Update Aktivitas sebagai Worker
```
1. Klik "Detail" pada aktivitas
2. Pilih status: Selesai/Tidak Dilakukan/Sedang Dikerjakan
3. Jika tidak dilakukan, masukkan alasan
4. Submit
```

### 6. Monitor sebagai Owner
```
1. Login kembali sebagai owner
2. Di dashboard, lihat progress bar dan statistik
3. Klik "Lihat Detail" pada tanaman untuk lihat detail aktivitas
```

### 7. Monitor sebagai Penyuluh
```
1. Register sebagai penyuluh baru
2. Di dashboard penyuluh, lihat semua tanaman dan progres
3. Lihat detail plant untuk memberikan rekomendasi
```

## Troubleshooting

### Error: "No application encryption key"
```
php artisan key:generate
```

### Error: "Unknown database 'sibucara'"
```
mysql -u root -e "CREATE DATABASE sibucara;"
```

### Error: "Migration not found"
```
php artisan migrate:refresh --seed
# Atau jika ada data penting:
php artisan migrate:status
# Cek mana yang belum jalan
```

### Error: 419 CSRF token mismatch
```
php artisan config:clear
php artisan cache:clear
```

## Next Steps

1. ✅ Database & Models setup
2. ✅ Controllers & Routes setup
3. ✅ Generate jadwal logic
4. ⏳ Update/Create views untuk semua halaman
5. ⏳ Add validation & error handling
6. ⏳ Add email notifications
7. ⏳ Add role-based middleware
8. ⏳ Add tests (Pest)
9. ⏳ Performance optimization

## Notes

- Sistem fokus pada Cabai Rawit dengan 3 varietas tetap
- Jadwal adalah 90 hari hingga panen
- Setiap aktivitas bisa memiliki catatan dari worker
- Sistem notes untuk guidance kondisi cuaca & rekomendasi
- Notifikasi auto-create untuk semua role saat generate jadwal
