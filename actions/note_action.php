<?php
/**
 * Backend CRUD Notes Controller
 * PHP Native Notes App
 */

// Memulai session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Memastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

// Import koneksi database
require_once __DIR__ . '/../config/database.php';

$user_id = $_SESSION['user_id'];

// ----------------------------------------------------
// PROSES DENGAN METHOD POST (Create & Update)
// ----------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // --- TAMBAH CATATAN ---
    if (isset($_POST['action']) && $_POST['action'] === 'create') {
        $title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS));
        $content = trim($_POST['content'] ?? '');
        $color = trim($_POST['color'] ?? '#6366f1');
        $is_pinned = isset($_POST['is_pinned']) ? 1 : 0;
        
        // Daftar warna valid (untuk validasi keamanan warna)
        $allowed_colors = ['#6366f1', '#3b82f6', '#10b981', '#f59e0b', '#ec4899', '#8b5cf6'];
        if (!in_array($color, $allowed_colors)) {
            $color = '#6366f1'; // warna default jika tidak valid
        }
        
        if (empty($title)) {
            $_SESSION['error'] = 'Judul catatan tidak boleh kosong!';
            header('Location: ../dashboard.php');
            exit;
        }
        
        try {
            $stmt = $pdo->prepare("INSERT INTO notes (user_id, title, content, color, is_pinned) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $title, $content, $color, $is_pinned]);
            
            $_SESSION['success'] = 'Catatan berhasil ditambahkan!';
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Gagal menyimpan catatan: ' . $e->getMessage();
        }
        
        header('Location: ../dashboard.php');
        exit;
    }
    
    // --- EDIT / UPDATE CATATAN ---
    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        $note_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS));
        $content = trim($_POST['content'] ?? '');
        $color = trim($_POST['color'] ?? '#6366f1');
        $is_pinned = isset($_POST['is_pinned']) ? 1 : 0;
        
        $allowed_colors = ['#6366f1', '#3b82f6', '#10b981', '#f59e0b', '#ec4899', '#8b5cf6'];
        if (!in_array($color, $allowed_colors)) {
            $color = '#6366f1';
        }
        
        if (!$note_id || empty($title)) {
            $_SESSION['error'] = 'ID Catatan atau Judul tidak valid!';
            header('Location: ../dashboard.php');
            exit;
        }
        
        try {
            // Validasi kepemilikan catatan terlebih dahulu
            $check_stmt = $pdo->prepare("SELECT id FROM notes WHERE id = ? AND user_id = ? LIMIT 1");
            $check_stmt->execute([$note_id, $user_id]);
            if (!$check_stmt->fetch()) {
                $_SESSION['error'] = 'Akses ditolak! Catatan tidak ditemukan atau bukan milik Anda.';
                header('Location: ../dashboard.php');
                exit;
            }
            
            // Update data
            $stmt = $pdo->prepare("UPDATE notes SET title = ?, content = ?, color = ?, is_pinned = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([$title, $content, $color, $is_pinned, $note_id, $user_id]);
            
            $_SESSION['success'] = 'Catatan berhasil diperbarui!';
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Gagal memperbarui catatan: ' . $e->getMessage();
        }
        
        header('Location: ../dashboard.php');
        exit;
    }
}

// ----------------------------------------------------
// PROSES DENGAN METHOD GET (Delete & Toggle Pin)
// ----------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    // --- HAPUS CATATAN ---
    if (isset($_GET['action']) && $_GET['action'] === 'delete') {
        $note_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        
        if (!$note_id) {
            $_SESSION['error'] = 'ID Catatan tidak valid!';
            header('Location: ../dashboard.php');
            exit;
        }
        
        try {
            // Validasi kepemilikan catatan
            $check_stmt = $pdo->prepare("SELECT id FROM notes WHERE id = ? AND user_id = ? LIMIT 1");
            $check_stmt->execute([$note_id, $user_id]);
            if (!$check_stmt->fetch()) {
                $_SESSION['error'] = 'Akses ditolak! Catatan tidak ditemukan.';
                header('Location: ../dashboard.php');
                exit;
            }
            
            // Delete data
            $stmt = $pdo->prepare("DELETE FROM notes WHERE id = ? AND user_id = ?");
            $stmt->execute([$note_id, $user_id]);
            
            $_SESSION['success'] = 'Catatan berhasil dihapus!';
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Gagal menghapus catatan: ' . $e->getMessage();
        }
        
        header('Location: ../dashboard.php');
        exit;
    }
    
    // --- TOGGLE PIN STATUS ---
    if (isset($_GET['action']) && $_GET['action'] === 'toggle_pin') {
        $note_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        
        if (!$note_id) {
            $_SESSION['error'] = 'ID Catatan tidak valid!';
            header('Location: ../dashboard.php');
            exit;
        }
        
        try {
            // Ambil data catatan saat ini & cek kepemilikan
            $stmt = $pdo->prepare("SELECT id, is_pinned FROM notes WHERE id = ? AND user_id = ? LIMIT 1");
            $stmt->execute([$note_id, $user_id]);
            $note = $stmt->fetch();
            
            if (!$note) {
                $_SESSION['error'] = 'Catatan tidak ditemukan!';
                header('Location: ../dashboard.php');
                exit;
            }
            
            // Balik status is_pinned (0 jadi 1, 1 jadi 0)
            $new_pin_status = $note['is_pinned'] == 1 ? 0 : 1;
            
            $update_stmt = $pdo->prepare("UPDATE notes SET is_pinned = ? WHERE id = ? AND user_id = ?");
            $update_stmt->execute([$new_pin_status, $note_id, $user_id]);
            
            $_SESSION['success'] = $new_pin_status == 1 ? 'Catatan berhasil disematkan!' : 'Sematkan catatan dibatalkan!';
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Gagal mengubah status sematan: ' . $e->getMessage();
        }
        
        header('Location: ../dashboard.php');
        exit;
    }
}

// Redirect jika akses tidak sah
header('Location: ../dashboard.php');
exit;
?>
