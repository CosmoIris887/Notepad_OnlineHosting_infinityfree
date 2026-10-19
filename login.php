<?php
/**
 * Halaman Login Pengguna
 * PHP Native Notes App
 */
$page_title = "Masuk Ke Akun Anda";
require_once __DIR__ . '/includes/header.php';

// Ambil pesan error/sukses dari session
$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
$old_login = $_SESSION['old_login'] ?? [];

// Hapus dari session setelah diambil agar tidak muncul lagi saat di-refresh
unset($_SESSION['error']);
unset($_SESSION['success']);
unset($_SESSION['old_login']);
?>

<div class="auth-container">
    <div class="auth-card glass-effect">
        <div class="auth-header">
            <h2>Selamat Datang Kembali</h2>
            <p>Silakan masuk untuk mengakses semua catatan Anda</p>
        </div>
        
        <!-- Menampilkan Alert Error jika Ada -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <!-- Menampilkan Alert Sukses jika Ada -->
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <!-- Form Login -->
        <form action="actions/auth_action.php" method="POST" id="loginForm" novalidate>
            <input type="hidden" name="action" value="login">
            
            <!-- Field Identifier (Username atau Email) -->
            <div class="form-group">
                <label for="identifier"><i class="fa-solid fa-user-tag"></i> Username atau Email</label>
                <input type="text" name="identifier" id="identifier" class="form-control" 
                       placeholder="Masukkan username atau email Anda" 
                       value="<?php echo htmlspecialchars($old_login['identifier'] ?? ''); ?>" required>
                <div class="invalid-feedback" id="identifierError"></div>
            </div>
            
            <!-- Field Password -->
            <div class="form-group">
                <label for="password"><i class="fa-solid fa-lock"></i> Kata Sandi</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" class="form-control" 
                           placeholder="Masukkan kata sandi Anda" required>
                    <button type="button" class="toggle-password" tabindex="-1">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
                <div class="invalid-feedback" id="passwordError"></div>
            </div>
            
            <!-- Tombol Submit -->
            <button type="submit" class="btn btn-primary btn-block">
                <i class="fa-solid fa-right-to-bracket"></i> Masuk Sekarang
            </button>
        </form>
        
        <div class="auth-footer">
            <p>Belum memiliki akun? <a href="register.php" class="link-accent">Daftar sekarang gratis</a></p>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
