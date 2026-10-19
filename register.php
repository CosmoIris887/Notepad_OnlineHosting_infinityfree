<?php
/**
 * Halaman Registrasi Pengguna
 * PHP Native Notes App
 */
$page_title = "Daftar Akun Baru";
require_once __DIR__ . '/includes/header.php';

// Ambil pesan error/sukses dari session
$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
$old = $_SESSION['old'] ?? [];

// Hapus dari session setelah diambil agar tidak muncul lagi saat di-refresh
unset($_SESSION['error']);
unset($_SESSION['success']);
unset($_SESSION['old']);
?>

<div class="auth-container">
    <div class="auth-card glass-effect">
        <div class="auth-header">
            <h2>Daftar Akun Baru</h2>
            <p>Mulai simpan dan kelola catatan Anda hari ini</p>
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
        
        <!-- Form Pendaftaran -->
        <form action="actions/auth_action.php" method="POST" id="registerForm" novalidate>
            <input type="hidden" name="action" value="register">
            
            <!-- Field Username -->
            <div class="form-group">
                <label for="username"><i class="fa-solid fa-user"></i> Username</label>
                <input type="text" name="username" id="username" class="form-control" 
                       placeholder="Masukkan username (a-z, 0-9, _)" 
                       value="<?php echo htmlspecialchars($old['username'] ?? ''); ?>" required>
                <div class="invalid-feedback" id="usernameError"></div>
            </div>
            
            <!-- Field Email -->
            <div class="form-group">
                <label for="email"><i class="fa-solid fa-envelope"></i> Alamat Email</label>
                <input type="email" name="email" id="email" class="form-control" 
                       placeholder="contoh@domain.com" 
                       value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>" required>
                <div class="invalid-feedback" id="emailError"></div>
            </div>
            
            <!-- Field Password -->
            <div class="form-group">
                <label for="password"><i class="fa-solid fa-lock"></i> Kata Sandi</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" class="form-control" 
                           placeholder="Minimal 6 karakter" required>
                    <button type="button" class="toggle-password" tabindex="-1">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
                <div class="invalid-feedback" id="passwordError"></div>
            </div>
            
            <!-- Field Konfirmasi Password -->
            <div class="form-group">
                <label for="confirm_password"><i class="fa-solid fa-circle-check"></i> Konfirmasi Kata Sandi</label>
                <div class="password-wrapper">
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" 
                           placeholder="Masukkan ulang kata sandi" required>
                    <button type="button" class="toggle-password" tabindex="-1">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
                <div class="invalid-feedback" id="confirmPasswordError"></div>
            </div>
            
            <!-- Tombol Submit -->
            <button type="submit" class="btn btn-primary btn-block">
                <i class="fa-solid fa-user-plus"></i> Daftar Sekarang
            </button>
        </form>
        
        <div class="auth-footer">
            <p>Sudah memiliki akun? <a href="login.php" class="link-accent">Masuk di sini</a></p>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
