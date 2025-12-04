# Quick Start Guide - Fitur Task Dosen

## File yang Dibuat/Dimodifikasi

### Controllers
- `app/Http/Controllers/Dosen/TaskController.php` - Main controller untuk CRUD task & manajemen partisipan

### Models
- `app/Models/Task.php` - Updated dengan cascade delete otomatis
- `app/Models/Dosen.php` - Sudah punya relasi tasks()
- `app/Models/Participant.php` - Sudah ada, relasi ke task & mahasiswa
- `app/Models/Mahasiswa.php` - Sudah ada

### Views
- `resources/views/dosen/tasks/index.blade.php` - Daftar task
- `resources/views/dosen/tasks/create.blade.php` - Form buat task
- `resources/views/dosen/tasks/edit.blade.php` - Form edit task
- `resources/views/dosen/tasks/show.blade.php` - Detail task + daftar peserta

### Routes
- `routes/web.php` - Updated dengan dosen task routes & participant routes

### Helpers
- `app/Helpers/FormatHelper.php` - Helper function untuk format menit ke jam
- `composer.json` - Updated autoload untuk include helper

### Layout
- `resources/views/layouts/app.blade.php` - Updated dengan link "Kelola Task" untuk dosen

## Fitur Utama

| Fitur | Deskripsi |
|-------|-----------|
| **CRUD Task** | Buat, baca, edit, hapus task |
| **Format Jam** | Simpan dalam menit, tampilkan sebagai "X jam Y menit" |
| **Terima/Tolak Partisipan** | Accept atau reject pendaftaran mahasiswa |
| **Status Penyelesaian** | Ubah status jadi "Selesai" atau "Tidak Selesai" |
| **Auto Jam Update** | Jam otomatis +/- saat status berubah jadi Selesai |
| **Cascade Delete** | Hapus task → partisipan otomatis terhapus |
| **Authorization** | Hanya dosen bisa kelola task mereka sendiri |

## Status Partisipan

### Status Verifikasi (status_acc)
- `NULL` / Menunggu - Belum diverifikasi dosen
- `Diterima` - Diterima untuk mengikuti task
- `Ditolak` - Ditolak untuk mengikuti task

### Status Penyelesaian (status_penyelesaian)
- `Pending` - Belum ada keputusan (default)
- `Selesai` - Mahasiswa menyelesaikan task (jam +)
- `Tidak Selesai` - Mahasiswa tidak menyelesaikan task (jam -)

## Testing Checklist

- [ ] Login sebagai dosen
- [ ] Buka menu "Kelola Task"
- [ ] Buat task baru dengan durasi 90 menit
- [ ] Verifikasi task muncul di daftar
- [ ] Klik "Lihat" untuk detail task
- [ ] Pastikan jam ditampilkan sebagai "1 jam 30 menit"
- [ ] Terima sebuah partisipan (jika ada)
- [ ] Ubah status jadi "Selesai" → verifikasi jam mahasiswa bertambah
- [ ] Edit task (ubah judul/jam)
- [ ] Hapus task → verifikasi partisipan otomatis terhapus

## Database Migrations

Sudah berjalan:
- `2025_11_28_152046_create_tasks_table` - Tabel tasks + participants
- Cascade delete sudah configured di migration

Tidak perlu migration baru karena struktur sudah existing.

## Styling

- **Framework**: Tailwind CSS (sudah di `app.blade.php`)
- **Icons**: Font Awesome (sudah di `app.blade.php`)
- **Responsive**: Mobile-friendly design

## API/Routes Endpoint

```
GET  /dosen/tasks              - List tasks
POST /dosen/tasks              - Create task
GET  /dosen/tasks/create       - Create form
GET  /dosen/tasks/{id}         - Show task
PUT  /dosen/tasks/{id}         - Update task
DELETE /dosen/tasks/{id}       - Delete task
GET  /dosen/tasks/{id}/edit    - Edit form

POST /dosen/participants/{id}/accept       - Accept participant
POST /dosen/participants/{id}/reject       - Reject participant
POST /dosen/participants/{id}/update-status - Update status penyelesaian
```

## Helper Function

```php
// Konversi menit ke format jam yang readable
formatJam(90)   // "1 jam 30 menit"
formatJam(45)   // "45 menit"
formatJam(120)  // "2 jam"
```

## Middleware & Guards

- `auth` - Harus login
- `role:2` - Hanya role Dosen (role_id = 2)
- Ownership verification di setiap aksi

## Next Steps

Fitur yang sudah siap untuk dikembangkan lebih lanjut:

1. **Mahasiswa View**: Buat tampilan untuk mahasiswa melihat tasks dan mendaftar
2. **Teknisi Dashboard**: Dashboard untuk teknisi melihat task & kompensasi
3. **Admin Reports**: Report task & kompensasi untuk admin
4. **Notifications**: Email/SMS notifikasi untuk perubahan status
5. **Export**: Export data task & kompensasi ke Excel/PDF
6. **Scoring System**: Sistem penilaian task
7. **Mobile App**: Mobile app untuk tracking kompensasi

## Commands Berguna

```bash
# Clear cache
php artisan cache:clear

# Regenerate autoloader
composer dump-autoload

# Test routes
php artisan route:list | grep dosen

# Seed data (jika ada seeder untuk task)
php artisan db:seed

# Serve aplikasi
php artisan serve

# Check migrations
php artisan migrate:status
```

## Support & Documentation

Lihat `DOSEN_TASK_DOCUMENTATION.md` untuk dokumentasi lengkap dengan:
- Penjelasan detail setiap fitur
- Method signature untuk setiap controller method
- Database schema lengkap
- Security practices
- Troubleshooting guide
