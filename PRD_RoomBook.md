# PRD - RoomBook
## Sistem Reservasi Ruangan Kampus

## 1. Ringkasan Produk

**RoomBook** adalah aplikasi web sederhana untuk membantu proses reservasi ruangan kampus secara lebih rapi, cepat, dan terdata. Aplikasi ini dibuat menggunakan **PHP Native** dan **PostgreSQL**.

Aplikasi ini cocok digunakan untuk kebutuhan kampus seperti peminjaman ruang kelas, ruang lab, ruang rapat, aula, atau ruangan lain yang dapat digunakan mahasiswa, dosen, maupun organisasi kampus.

## 2. Latar Belakang

Di lingkungan kampus, proses peminjaman ruangan sering dilakukan secara manual melalui chat, kertas, atau konfirmasi langsung ke pihak terkait. Cara ini rawan menimbulkan masalah seperti jadwal bentrok, data peminjam tidak tercatat, status reservasi tidak jelas, dan sulit melihat riwayat penggunaan ruangan.

Karena itu, RoomBook dibuat sebagai sistem reservasi ruangan berbasis web agar data ruangan, jadwal pemakaian, dan status reservasi bisa dikelola dengan lebih mudah.

## 3. Tujuan Produk

Tujuan utama RoomBook adalah:

1. Mempermudah proses pengajuan reservasi ruangan kampus.
2. Membantu admin mengelola data ruangan.
3. Mengurangi risiko bentrok jadwal pemakaian ruangan.
4. Menyediakan informasi status reservasi secara jelas.
5. Menyimpan riwayat reservasi agar mudah dicek kembali.
6. Menjadi proyek portofolio web berbasis PHP dan PostgreSQL.

## 4. Target Pengguna

### 4.1 Admin
Admin bertugas mengelola data ruangan dan data reservasi.

Kebutuhan admin:
- Melihat daftar ruangan.
- Menambah, mengubah, dan menghapus data ruangan.
- Melihat daftar reservasi.
- Mengubah status reservasi menjadi disetujui, ditolak, atau selesai.
- Melihat ringkasan data pada dashboard.

### 4.2 Pengguna Umum
Pengguna umum bisa berupa mahasiswa, dosen, atau organisasi kampus yang ingin mengajukan reservasi ruangan.

Kebutuhan pengguna:
- Melihat daftar ruangan yang tersedia.
- Mengajukan reservasi ruangan.
- Melihat status pengajuan reservasi.
- Mengecek jadwal ruangan.

## 5. Ruang Lingkup Project

### 5.1 Fitur Utama

#### A. Dashboard
Dashboard menampilkan ringkasan data seperti:
- Total ruangan.
- Total reservasi.
- Reservasi pending.
- Reservasi disetujui.
- Reservasi ditolak.
- Reservasi selesai.

#### B. Manajemen Ruangan
Admin dapat mengelola data ruangan.

Data ruangan:
- Nama ruangan.
- Lokasi.
- Kapasitas.
- Fasilitas.
- Status ruangan.

Fitur:
- Tambah ruangan.
- Edit ruangan.
- Hapus ruangan.
- Cari ruangan.
- Filter status ruangan.

#### C. Manajemen Reservasi
Sistem dapat menyimpan data reservasi ruangan.

Data reservasi:
- Nama peminjam.
- Email atau kontak peminjam.
- Nama kegiatan.
- Ruangan yang dipilih.
- Tanggal reservasi.
- Jam mulai.
- Jam selesai.
- Keperluan.
- Status reservasi.

Fitur:
- Tambah reservasi.
- Edit reservasi.
- Hapus reservasi.
- Update status reservasi.
- Validasi jadwal bentrok.
- Filter berdasarkan status.
- Filter berdasarkan tanggal.

#### D. Jadwal Ruangan
Halaman jadwal menampilkan daftar penggunaan ruangan berdasarkan tanggal.

Fitur:
- Melihat jadwal reservasi.
- Filter berdasarkan tanggal.
- Filter berdasarkan ruangan.

#### E. Riwayat Reservasi
Halaman riwayat menampilkan reservasi yang sudah selesai atau ditolak.

Fitur:
- Melihat data reservasi lama.
- Cari berdasarkan nama peminjam atau nama kegiatan.
- Filter berdasarkan status.

#### F. UI Responsive
Tampilan dibuat responsif agar nyaman dibuka melalui laptop maupun perangkat mobile.

## 6. Fitur yang Tidak Masuk Scope Awal

Agar project tetap realistis untuk portofolio, fitur berikut tidak dibuat pada versi awal:

- Login multi-role.
- Notifikasi email otomatis.
- Pembayaran.
- Kalender interaktif kompleks.
- Export PDF.
- Upload surat izin.
- Integrasi Google Calendar.

Fitur tersebut bisa menjadi pengembangan versi berikutnya.

## 7. User Flow

### 7.1 Flow Admin Mengelola Ruangan

1. Admin membuka halaman ruangan.
2. Admin melihat daftar ruangan.
3. Admin menambah data ruangan baru.
4. Admin mengubah data jika ada kesalahan.
5. Admin menghapus data ruangan jika sudah tidak digunakan.

### 7.2 Flow Pengajuan Reservasi

1. Pengguna membuka halaman reservasi.
2. Pengguna memilih ruangan.
3. Pengguna mengisi data reservasi.
4. Sistem mengecek apakah jadwal bentrok.
5. Jika tidak bentrok, reservasi disimpan dengan status pending.
6. Admin mengecek reservasi masuk.
7. Admin menyetujui atau menolak reservasi.

### 7.3 Flow Validasi Jadwal Bentrok

1. Pengguna memilih ruangan.
2. Pengguna mengisi tanggal, jam mulai, dan jam selesai.
3. Sistem mengecek apakah ada reservasi lain pada ruangan yang sama, tanggal yang sama, dan waktu yang bertabrakan.
4. Jika ada bentrok, sistem menolak penyimpanan data.
5. Jika tidak ada bentrok, sistem menyimpan reservasi.

## 8. Struktur Halaman

1. Dashboard
2. Data Ruangan
3. Tambah Ruangan
4. Edit Ruangan
5. Data Reservasi
6. Tambah Reservasi
7. Edit Reservasi
8. Jadwal Ruangan
9. Riwayat Reservasi

## 9. Struktur Database Awal

### 9.1 Tabel rooms

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| id | SERIAL PRIMARY KEY | ID ruangan |
| name | VARCHAR(100) | Nama ruangan |
| location | VARCHAR(150) | Lokasi ruangan |
| capacity | INTEGER | Kapasitas ruangan |
| facilities | TEXT | Fasilitas ruangan |
| status | VARCHAR(20) | Status ruangan |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diubah |

Status ruangan:
- available
- maintenance
- unavailable

### 9.2 Tabel reservations

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| id | SERIAL PRIMARY KEY | ID reservasi |
| room_id | INTEGER | Relasi ke tabel rooms |
| borrower_name | VARCHAR(100) | Nama peminjam |
| borrower_contact | VARCHAR(100) | Kontak peminjam |
| activity_name | VARCHAR(150) | Nama kegiatan |
| reservation_date | DATE | Tanggal reservasi |
| start_time | TIME | Jam mulai |
| end_time | TIME | Jam selesai |
| purpose | TEXT | Keperluan |
| status | VARCHAR(20) | Status reservasi |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diubah |

Status reservasi:
- pending
- approved
- rejected
- completed

## 10. Validasi Sistem

Validasi yang dibutuhkan:

1. Nama ruangan wajib diisi.
2. Kapasitas harus berupa angka.
3. Nama peminjam wajib diisi.
4. Nama kegiatan wajib diisi.
5. Tanggal reservasi wajib diisi.
6. Jam mulai wajib diisi.
7. Jam selesai wajib diisi.
8. Jam selesai harus lebih besar dari jam mulai.
9. Ruangan tidak boleh dipilih jika statusnya maintenance atau unavailable.
10. Jadwal tidak boleh bentrok dengan reservasi yang statusnya pending atau approved.

## 11. Tampilan dan Gaya UI

Konsep tampilan:

- Bersih dan modern.
- Warna utama biru atau indigo.
- Sidebar sederhana.
- Card ringkasan pada dashboard.
- Tabel data yang rapi.
- Tombol aksi jelas.
- Badge status berwarna.
- Layout responsif.

Contoh warna status:
- Pending: kuning.
- Approved: hijau.
- Rejected: merah.
- Completed: biru/abu.

## 12. Teknologi yang Digunakan

- PHP Native.
- PostgreSQL.
- HTML.
- CSS.
- Bootstrap atau Tailwind CSS.
- JavaScript sederhana.
- pgAdmin 4.
- Laragon atau server lokal PHP.

## 13. Kriteria Selesai

Project dianggap selesai jika:

1. Database sudah dibuat.
2. Koneksi PHP ke PostgreSQL berhasil.
3. CRUD ruangan berjalan.
4. CRUD reservasi berjalan.
5. Validasi bentrok jadwal berjalan.
6. Dashboard menampilkan ringkasan data.
7. Jadwal ruangan bisa difilter.
8. Riwayat reservasi bisa dilihat.
9. UI sudah responsif.
10. README.md sudah dibuat.
11. Project siap dimasukkan ke portofolio.

## 14. Pengembangan Lanjutan

Fitur yang bisa ditambahkan nanti:

1. Login admin.
2. Role mahasiswa, dosen, dan admin.
3. Export laporan PDF.
4. Upload surat izin kegiatan.
5. Notifikasi email.
6. Kalender reservasi.
7. Cetak bukti reservasi.
8. Integrasi Google Calendar.

## 15. Ringkasan Portofolio

RoomBook adalah sistem reservasi ruangan kampus berbasis web yang dibuat menggunakan PHP dan PostgreSQL. Sistem ini membantu mengelola data ruangan, pengajuan reservasi, pengecekan jadwal, validasi bentrok, serta status peminjaman ruangan. Project ini cocok sebagai portofolio karena memiliki alur bisnis yang jelas, fitur CRUD, validasi data, relasi database, dan dashboard ringkasan.
