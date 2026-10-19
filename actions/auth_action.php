<?php
/**
 * Backend Proses Autentikasi (Register, Login, & Logout)
 * PHP Native Notes App
 */

// Memulai session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Import koneksi database
require_once __DIR__ . '/../config/database.php';

// Menangani permintaan POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // ----------------------------------------------------
    // PROSES REGISTER
    // ----------------------------------------------------
    if (isset($_POST['action']) && $_POST['action'] === 'register') {
        // Ambil dan bersihkan input
        $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS));
        $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        // Simpan input lama agar user tidak perlu mengetik ulang jika error
        $_SESSION['old'] = [
            'username' => $username,
            'email' => $email
        ];
        
        // Validasi input
        if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            $_SESSION['error'] = 'Semua field wajib diisi!';
            header('Location: ../register.php');
            exit;
        }
        
        // Validasi format email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Format email tidak valid!';
            header('Location: ../register.php');
            exit;
        }
        
        // Validasi panjang username
        if (strlen($username) < 3 || strlen($username) > 20) {
            $_SESSION['error'] = 'Username harus terdiri dari 3 - 20 karakter!';
            header('Location: ../register.php');
            exit;
        }
        
        // Validasi karakter username (hanya huruf, angka, underscore)
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            $_SESSION['error'] = 'Username hanya boleh berisi huruf, angka, dan underscore (_)!';
            header('Location: ../register.php');
            exit;
        }
        
        // Validasi panjang password
        if (strlen($password) < 6) {
            $_SESSION['error'] = 'Password minimal harus 6 karakter!';
            header('Location: ../register.php');
            exit;
        }
        
        // Validasi konfirmasi password
        if ($password !== $confirm_password) {
            $_SESSION['error'] = 'Konfirmasi password tidak cocok!';
            header('Location: ../register.php');
            exit;
        }
        
        try {
            // Cek apakah username atau email sudah terdaftar
            $stmt = $pdo->prepare("SELECT id, username, email FROM users WHERE username = ? OR email = ? LIMIT 1");
            $stmt->execute([$username, $email]);
            $existing_user = $stmt->fetch();
            
            if ($existing_user) {
                if ($existing_user['username'] === $username) {
                    $_SESSION['error'] = 'Username sudah digunakan oleh orang lain!';
                } else if ($existing_user['email'] === $email) {
                    $_SESSION['error'] = 'Email sudah terdaftar!';
                }
                header('Location: ../register.php');
                exit;
            }
            
            // Hash password secara aman
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            
            // Insert user baru ke database
            $insert_stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $insert_stmt->execute([$username, $email, $hashed_password]);
            
            // Hapus session input lama dan set pesan sukses
            unset($_SESSION['old']);
            $_SESSION['success'] = 'Registrasi berhasil! Silakan login menggunakan akun Anda.';
            header('Location: ../login.php');
            exit;
            
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Terjadi kesalahan sistem: ' . $e->getMessage();
            header('Location: ../register.php');
            exit;
        }
    }
    
    // ----------------------------------------------------
    // PROSES LOGIN
    // ----------------------------------------------------
    if (isset($_POST['action']) && $_POST['action'] === 'login') {
        // Ambil dan bersihkan input
        $identifier = trim(filter_input(INPUT_POST, 'identifier', FILTER_SANITIZE_SPECIAL_CHARS)); // bisa username atau email
        $password = $_POST['password'] ?? '';
        
        // Simpan input lama
        $_SESSION['old_login'] = [
            'identifier' => $identifier
        ];
        
        if (empty($identifier) || empty($password)) {
            $_SESSION['error'] = 'Username/Email dan Password wajib diisi!';
            header('Location: ../login.php');
            exit;
        }
        
        try {
            // Cari user berdasarkan username atau email
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1");
            $stmt->execute([$identifier, $identifier]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                // Login sukses, buat session baru (regenerate id demi keamanan)
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                
                // Hapus data temporary login
                unset($_SESSION['old_login']);
                unset($_SESSION['error']);
                
                header('Location: ../dashboard.php');
                exit;
            } else {
                // Login gagal
                $_SESSION['error'] = 'Username/Email atau Password salah!';
                header('Location: ../login.php');
                exit;
            }
            
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Terjadi kesalahan sistem: ' . $e->getMessage();
            header('Location: ../login.php');
            exit;
        }
    }
}

// ----------------------------------------------------
// PROSES LOGOUT (Bisa dipicu GET / Logout Link)
// ----------------------------------------------------
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    // Unset semua session variabel
    $_SESSION = array();
    
    // Hapus session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Hancurkan session
    session_destroy();
    
    // Mulai session baru hanya untuk mengirim flash message sukses logout
    session_start();
    $_SESSION['success'] = 'Anda telah berhasil keluar dari akun.';
    header('Location: ../login.php');
    exit;
}

// Jika diakses langsung tanpa parameter / POST
header('Location: ../index.php');
exit;
?>
