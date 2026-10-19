Aplikasi web Notes App sederhana yang dibangun menggunakan **PHP Native, MySQL, HTML5, CSS3, dan JavaScript**. Aplikasi ini dilengkapi dengan tampilan modern bertema *Space Dark-Glassmorphism* yang responsif untuk berbagai ukuran perangkat (desktop, tablet, dan handphone).

---

## Fitur Utama

1. **Autentikasi Pengguna**: Sistem Registrasi & Login yang aman menggunakan enkripsi password BCrypt.
2. **Dashboard Interaktif**: Halaman panel utama dengan menu navigasi responsif dan animasi yang mulus.
3. **CRUD Catatan Lengkap**:
   - **Create (Tambah)**: Menambahkan catatan dengan judul, isi, kategori warna kustom, dan opsi menyematkan (pin).
   - **Read (Melihat)**: Membaca detail catatan secara langsung di dashboard atau melalui modal detail.
   - **Update (Edit)**: Memperbarui judul, isi, warna, dan status sematan (pin) catatan secara cepat.
   - **Delete (Hapus)**: Menghapus catatan secara permanen melalui modal konfirmasi kustom yang aman.
4. **Pencarian Real-Time (Live Search)**: Memfilter catatan secara instan saat mengetik kata kunci pada kolom pencarian tanpa perlu me-reload halaman.
5. **Penyematan Catatan (Pin)**: Menyematkan catatan penting di bagian paling atas dashboard.
6. **Kategori Warna Kustom**: 6 skema pilihan warna kartu catatan premium (Indigo, Biru, Emerald, Amber, Pink, Violet).
7. **Keamanan Ekstra**: Mencegah serangan *SQL Injection* menggunakan **PDO Prepared Statements** dan mengamankan input dari *XSS (Cross-Site Scripting)*.

---

## 📂 Struktur Project

```text
tugaswspkonto/
│
├── actions/
│   ├── auth_action.php    # Handler backend untuk Login, Register, & Logout
│   └── note_action.php    # Handler backend untuk CRUD Catatan (Add, Edit, Delete, Pin)
│
├── config/
│   └── database.php       # Konfigurasi koneksi database PDO (Local & Hosting)
│
├── css/
│   └── style.css          # Desain & Styling utama (Dark-Glassmorphism, Grid, Responisve)
│
├── database/
│   └── notes_db.sql       # Skema database MySQL yang siap diimport
│
├── includes/
│   ├── header.php         # Template header global (Session check, head tags, nav menu)
│   └── footer.php         # Template footer global (Footer copyright, script loader)
│
├── js/
│   ├── auth.js            # Validasi form autentikasi client-side & eye password toggle
│   └── main.js            # Logika dashboard (live search, event modal, binding data)
│
├── index.php              # Halaman landing utama aplikasi
├── login.php              # Halaman form login
├── register.php           # Halaman form pendaftaran akun
├── dashboard.php          # Dashboard catatan setelah login
└── README.md              # Panduan instalasi dan deployment ini
```

---

## 💻 Cara Menjalankan di Localhost (XAMPP)

### Langkah 1: Persiapan Folder Project
1. Salin seluruh folder project `tugaswspkonto` ke dalam folder `htdocs` instalasi XAMPP Anda:
   - Windows: `C:\xampp\htdocs\tugaswspkonto`
   - macOS: `/Applications/XAMPP/htdocs/tugaswspkonto`

### Langkah 2: Import Database ke phpMyAdmin
1. Jalankan panel kontrol XAMPP dan aktifkan modul **Apache** dan **MySQL**.
2. Buka browser dan akses halaman **phpMyAdmin** (`http://localhost/phpmyadmin/`).
3. Buat database baru dengan nama `notes_db`:
   - Klik tab **Baru** / **New**.
   - Isi nama basis data dengan `notes_db` lalu klik **Buat** / **Create**.
4. Pilih database `notes_db` yang baru dibuat, lalu klik tab **Impor** / **Import** di bagian atas.
5. Klik **Pilih File** / **Choose File** dan arahkan ke berkas SQL di dalam project:
   `htdocs/tugaswspkonto/database/notes_db.sql`.
6. Gulir ke bawah lalu klik tombol **Impor** / **Go** di bagian bawah halaman.

### Langkah 3: Jalankan Aplikasi
1. Buka browser Anda lalu akses URL berikut:
   `http://localhost/tugaswspkonto/`
2. Anda sudah bisa mencoba melakukan pendaftaran akun baru, masuk, dan mulai mengelola catatan!

---

## 🌐 Cara Deploy ke InfinityFree Hosting

InfinityFree adalah hosting gratis yang sangat populer untuk aplikasi PHP & MySQL. Ikuti langkah berikut untuk meng-online-kan project Anda:

### Langkah 1: Buat Akun & Siapkan Database di InfinityFree
1. Daftar atau masuk ke Client Area [InfinityFree](https://infinityfree.net/).
2. Buat akun hosting baru dan akses **Control Panel (cPanel / VistaPanel)** Anda.
3. Di cPanel, cari menu **MySQL Databases**.
4. Buat database baru (misalnya dengan nama akhir `notes`). Catat detail informasi berikut yang diberikan oleh InfinityFree:
   - **MySQL Hostname** (biasanya format: `sqlXXX.infinityfree.com`)
   - **MySQL Username** (biasanya format: `if0_xxxxxx`)
   - **MySQL Password** (password control panel Anda)
   - **MySQL Database Name** (biasanya format: `if0_xxxxxx_notes`)
5. Buka **phpMyAdmin** dari cPanel InfinityFree, pilih database tersebut, dan impor file `database/notes_db.sql` seperti langkah lokal di atas.

### Langkah 2: Unggah File Project ke Hosting
1. Di cPanel InfinityFree, buka **Online File Manager** atau gunakan software FTP client (seperti FileZilla).
2. Masuk ke direktori **`htdocs`** di hosting Anda.
3. Unggah seluruh file dan folder project `tugaswspkonto` ke dalam folder `htdocs` hosting tersebut.

### Langkah 3: Konfigurasi Database di Hosting
1. Di dalam File Manager hosting Anda, buka dan edit file konfigurasi database yang terletak di:
   `config/database.php`
2. Ubah baris konfigurasi database (`define(...)`) menggunakan detail database InfinityFree yang telah Anda catat sebelumnya:
   ```php
   define('DB_HOST', 'sqlXXX.infinityfree.com'); // Ganti dengan MySQL Hostname InfinityFree Anda
   define('DB_NAME', 'if0_xxxxxx_notes');        // Ganti dengan Nama Database InfinityFree Anda
   define('DB_USER', 'if0_xxxxxx');              // Ganti dengan Username Database InfinityFree Anda
   define('DB_PASS', 'password_cpanel_anda');     // Ganti dengan Password Database InfinityFree Anda
   ```
3. Simpan perubahan file (`Save`).
4. Akses domain gratis yang diberikan oleh InfinityFree (misal: `http://domainanda.infinityfreeapp.com/`). Aplikasi Notes App Anda kini sudah online dan siap digunakan!
