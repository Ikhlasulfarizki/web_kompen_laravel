# Dokumentasi Fitur Task Dosen

## Overview
Fitur ini memungkinkan dosen untuk mengelola task (pekerjaan) yang diberikan kepada mahasiswa, serta mengelola partisipan yang mendaftar untuk task tersebut. Dosen dapat menerima/menolak partisipan dan mengubah status penyelesaian.

## Fitur-Fitur Utama

### 1. **CRUD Task (Create, Read, Update, Delete)**
Dosen dapat membuat, melihat, mengedit, dan menghapus task.

#### Data yang Disimpan:
- **Judul**: Nama/judul dari task
- **Deskripsi**: Penjelasan detail task (opsional)
- **Lokasi**: Tempat task akan dilaksanakan
- **Tanggal**: Tanggal pelaksanaan task
- **Jam Mulai & Jam Selesai**: Waktu operasional task
- **Jumlah Jam**: Durasi task **dalam menit** (contoh: 90 menit = 1 jam 30 menit)
- **Kuota**: Jumlah maksimal peserta yang bisa mendaftar

### 2. **Manajemen Partisipan**

#### A. Melihat Informasi Mahasiswa
Dosen dapat melihat detail mahasiswa yang mendaftar:
- Nama
- NPM (Nomor Pokok Mahasiswa)
- Jumlah Jam yang dimiliki
- Jurusan
- Program Studi
- Kelas

#### B. Menerima/Menolak Partisipan
Dosen dapat menerima atau menolak pendaftaran mahasiswa untuk task tertentu.
- Status akan berubah menjadi "Diterima" atau "Ditolak"
- Hanya partisipan yang diterima yang akan menyelesaikan task

#### C. Mengubah Status Penyelesaian
Dosen dapat mengubah status penyelesaian partisipan:
- **Pending**: Status default saat partisipan baru terdaftar
- **Selesai**: Mahasiswa telah menyelesaikan task
  - *Efek*: Jam kerja otomatis ditambahkan ke total jam mahasiswa
  - *Jumlah jam yang ditambah*: Sesuai dengan `jmlh_jam` dari task (dalam menit)
- **Tidak Selesai**: Mahasiswa tidak menyelesaikan task
  - *Efek*: Jam kerja akan dikurangi dari total jam (jika sebelumnya Selesai)

### 3. **Cascade Delete**
Ketika dosen menghapus task:
- Semua partisipan yang terdaftar untuk task tersebut **otomatis dihapus**
- Tidak ada lagi hubungan antara mahasiswa dan task yang sudah dihapus

## Route dan Endpoint

### Dosen Tasks
```
GET    /dosen/tasks                          -> Index (Daftar Task)
POST   /dosen/tasks                          -> Store (Simpan Task Baru)
GET    /dosen/tasks/create                   -> Create (Form Buat Task)
GET    /dosen/tasks/{task}                   -> Show (Detail Task)
PUT    /dosen/tasks/{task}                   -> Update (Update Task)
DELETE /dosen/tasks/{task}                   -> Destroy (Hapus Task)
GET    /dosen/tasks/{task}/edit              -> Edit (Form Edit Task)
```

### Dosen Participants
```
POST   /dosen/participants/{participant}/accept        -> Accept Participant
POST   /dosen/participants/{participant}/reject        -> Reject Participant
POST   /dosen/participants/{participant}/update-status -> Update Completion Status
```

## Controller Methods

### `TaskController@index()`
- Menampilkan daftar semua task milik dosen yang login
- Menampilkan jumlah peserta untuk setiap task

### `TaskController@create()`
- Menampilkan form untuk membuat task baru

### `TaskController@store(Request $request)`
- Menyimpan task baru ke database
- Validasi input dan simpan dengan `id_dosen` dari user yang login

### `TaskController@show(Task $task)`
- Menampilkan detail task beserta daftar peserta
- Verifikasi bahwa task milik dosen yang login

### `TaskController@edit(Task $task)`
- Menampilkan form untuk edit task

### `TaskController@update(Request $request, Task $task)`
- Mengupdate data task di database
- Validasi dan verifikasi kepemilikan task

### `TaskController@destroy(Task $task)`
- Menghapus task dan semua partisipan yang terkait (cascade delete)
- Verifikasi kepemilikan task

### `TaskController@acceptParticipant(Participant $participant)`
- Mengubah status partisipan menjadi "Diterima"
- Verifikasi bahwa task milik dosen yang login

### `TaskController@rejectParticipant(Participant $participant)`
- Mengubah status partisipan menjadi "Ditolak"
- Verifikasi bahwa task milik dosen yang login

### `TaskController@updateParticipantStatus(Request $request, Participant $participant)`
- Mengubah status penyelesaian partisipan
- Otomatis menambah/mengurangi jam mahasiswa jika status berubah menjadi "Selesai"
- Verifikasi bahwa task milik dosen yang login

## Model Relationships

### Task Model
```php
// Relasi ke Dosen
public function dosen()
{
    return $this->belongsTo(Dosen::class, 'id_dosen');
}

// Relasi ke Participants dengan cascade delete
public function participants()
{
    return $this->hasMany(Participant::class, 'id_task');
}
```

### Participant Model
```php
// Relasi ke Task
public function task()
{
    return $this->belongsTo(Task::class, 'id_task');
}

// Relasi ke Mahasiswa
public function mahasiswa()
{
    return $this->belongsTo(Mahasiswa::class, 'id_mhs');
}
```

## Validasi Input

### Task Creation/Update
- **judul**: Required, string, max 255 characters
- **deskripsi**: Optional, string
- **lokasi**: Required, string, max 255 characters
- **tanggal_waktu**: Required, date format
- **kuota**: Required, integer, min 1
- **jam_mulai**: Required, time format (H:i)
- **jam_selesai**: Required, time format (H:i)
- **jmlh_jam**: Required, integer, min 1 (dalam menit)

### Status Update
- **status_penyelesaian**: Required, in:Selesai,Tidak Selesai

## Helper Functions

### `formatJam($menit)`
Mengkonversi menit menjadi format jam yang readable.

**Parameter:**
- `$menit`: Jumlah menit (integer)

**Return:**
- String dalam format "X jam Y menit" atau variasi lainnya

**Contoh:**
```php
formatJam(90)   // Output: "1 jam 30 menit"
formatJam(45)   // Output: "45 menit"
formatJam(120)  // Output: "2 jam"
formatJam(0)    // Output: "0 menit"
```

## Blade Views

### `/resources/views/dosen/tasks/index.blade.php`
Menampilkan daftar semua task dosen dengan tombol aksi (View, Edit, Delete)

### `/resources/views/dosen/tasks/create.blade.php`
Form untuk membuat task baru

### `/resources/views/dosen/tasks/edit.blade.php`
Form untuk mengedit task yang ada

### `/resources/views/dosen/tasks/show.blade.php`
Detail task dan daftar lengkap partisipan dengan tombol aksi (Accept, Reject, Update Status)

## Middleware & Authorization

Semua route dosen protected dengan middleware `role:2` yang memastikan hanya user dengan role "Dosen" (role_id = 2) yang bisa mengakses.

Setiap action juga memverifikasi bahwa:
- Task milik dosen yang login (via `id_dosen`)
- Partisipan terdaftar di task yang milik dosen yang login

## Cara Menggunakan

### Membuat Task Baru
1. Login sebagai dosen
2. Klik "Kelola Task" di sidebar
3. Klik "Buat Task Baru"
4. Isi semua field yang diperlukan
5. Klik "Simpan Task"

### Melihat Detail Task dan Partisipan
1. Di halaman daftar task, klik tombol "Lihat" pada task yang diinginkan
2. Lihat detail task dan statistik peserta
3. Lihat tabel daftar peserta dengan informasi lengkap

### Menerima/Menolak Partisipan
1. Di halaman detail task, cari partisipan yang ingin diterima/ditolak
2. Klik tombol "✓" untuk menerima atau "✕" untuk menolak
3. Status akan berubah otomatis

### Mengubah Status Penyelesaian
1. Di halaman detail task, klik dropdown "Status" pada partisipan
2. Pilih "Selesai" atau "Tidak Selesai"
3. Sistem akan otomatis update jam mahasiswa

### Edit Task
1. Di halaman detail task atau daftar task, klik tombol "Edit"
2. Ubah field yang diperlukan
3. Klik "Simpan Perubahan"

### Menghapus Task
1. Di halaman daftar task, klik tombol "Hapus"
2. Confirm dialog akan muncul
3. Klik "OK" untuk konfirmasi, semua partisipan akan otomatis terhapus

## Database Schema

### tasks table
```
id              - Primary Key (Auto Increment)
judul           - String(255)
deskripsi       - LongText (nullable)
lokasi          - String(255)
tanggal_waktu   - DateTime
kuota           - Integer
jam_mulai       - String
jam_selesai     - String
jmlh_jam        - Integer (dalam menit)
id_dosen        - Foreign Key (refs: dosen.id) - onDelete: cascade
created_at      - Timestamp
updated_at      - Timestamp
```

### participants table
```
id                    - Primary Key (Auto Increment)
id_task               - Foreign Key (refs: tasks.id) - onDelete: cascade
id_mhs                - Foreign Key (refs: mahasiswa.id) - onDelete: cascade
status_pendaftaran    - String(255) (nullable)
status_penyelesaian   - LongText (nullable, default: "Pending")
status_acc            - String(255) (nullable)
created_at            - Timestamp
updated_at            - Timestamp
```

## Keamanan

1. **Authentication**: Semua route memerlukan login
2. **Authorization**: Middleware `role:2` memastikan hanya dosen
3. **Ownership Verification**: Setiap aksi memverifikasi task milik dosen
4. **CSRF Protection**: Semua form dilindungi dengan CSRF token
5. **Input Validation**: Semua input divalidasi sebelum disimpan
6. **SQL Injection Prevention**: Menggunakan parameterized queries via Eloquent ORM

## Troubleshooting

### Helper function tidak terbaca
**Solusi:** Jalankan `composer dump-autoload` untuk regenerate autoloader

### Route tidak terdaftar
**Solusi:** 
1. Clear cache dengan `php artisan cache:clear`
2. Jalankan `php artisan route:cache` untuk cache route
3. Restart server

### Partisipan tidak hilang saat task dihapus
**Solusi:** Pastikan migration sudah dijalankan dan database sudah update dengan cascade delete

## Future Enhancements

Fitur-fitur yang bisa ditambahkan di masa depan:
1. **Export/Import**: Export task ke Excel atau import dari Excel
2. **Attendance**: Tracking kehadiran peserta
3. **Scoring**: Sistem penilaian/scoring untuk peserta
4. **Reminder**: Notifikasi otomatis untuk task yang akan datang
5. **Reporting**: Laporan komprehensif tentang task dan peserta
6. **Recurring Tasks**: Task yang berulang mingguan/bulanan
7. **Task Templates**: Template task yang bisa direuse
