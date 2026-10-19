<?php
/**
 * Konfigurasi Database - PHP Native Notes App
 * 
 * Silakan sesuaikan pengaturan di bawah ini saat mendeploy aplikasi ke hosting (seperti InfinityFree).
 */

// --- KONFIGURASI DATABASE ---
define('DB_HOST', 'sql200.infinityfree.com');       // Host database (InfinityFree biasanya menggunakan: sqlXXX.infinityfree.com)
define('DB_NAME', 'if0_42259657_notes_db');        // Nama database (InfinityFree biasanya berformat: if0_xxxxxx_notes_db)
define('DB_USER', 'if0_42259657');            // Username database (InfinityFree biasanya berformat: if0_xxxxxx)
define('DB_PASS', '451699Wj');                // Password database (InfinityFree password cPanel Anda)
define('DB_CHARSET', 'utf8mb4');

try {
    // DSN (Data Source Name)
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    
    // Opsi PDO untuk penanganan error dan fetching array secara default
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    
    // Inisialisasi koneksi PDO
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    
} catch (PDOException $e) {
    // Tampilan error koneksi yang aman untuk user namun informatif bagi developer
    die("Koneksi ke database gagal! Silakan cek file konfigurasi `config/database.php`. <br>Detail Error: " . $htmlspecialchars_error = htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}
?>
