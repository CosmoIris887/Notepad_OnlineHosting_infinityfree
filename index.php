<?php
/**
 * Halaman Landing (Utama)
 * PHP Native Notes App
 */
$page_title = "Simpan Ide-Ide Cemerlangmu";
require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content glass-effect">
        <div class="hero-badge"><i class="fa-solid fa-sparkles"></i> Cara Baru Mengelola Catatan</div>
        <h1 class="hero-title">Tulis, Atur, & Temukan Ide Anda Dengan <span class="gradient-text">Mudah</span></h1>
        <p class="hero-subtitle">
            Antigravity Notes menyediakan ruang digital yang bersih, aman, dan super responsif untuk menyimpan pemikiran, tugas, dan catatan kreatif Anda kapan saja dan di mana saja.
        </p>
        
        <div class="hero-actions">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="dashboard.php" class="btn btn-primary btn-large">
                    <i class="fa-solid fa-circle-right"></i> Masuk Ke Dashboard
                </a>
            <?php else: ?>
                <a href="register.php" class="btn btn-primary btn-large">
                    Mulai Gratis <i class="fa-solid fa-arrow-right"></i>
                </a>
                <a href="login.php" class="btn btn-secondary btn-large">
                    Sudah Punya Akun?
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Features Grid -->
<section class="features-section">
    <h2 class="section-title">Fitur Unggulan Yang Memudahkan Anda</h2>
    <div class="features-grid">
        <!-- Feature 1 -->
        <div class="feature-card glass-effect">
            <div class="feature-icon bg-indigo"><i class="fa-solid fa-magnifying-glass"></i></div>
            <h3>Pencarian Instan</h3>
            <p>Temukan catatan Anda dalam hitungan milidetik dengan fitur live-search di sisi client yang responsif.</p>
        </div>
        
        <!-- Feature 2 -->
        <div class="feature-card glass-effect">
            <div class="feature-icon bg-pink"><i class="fa-solid fa-thumbtack"></i></div>
            <h3>Sematkan Catatan</h3>
            <p>Pin catatan terpenting agar selalu berada di posisi teratas dashboard sehingga mudah diakses.</p>
        </div>
        
        <!-- Feature 3 -->
        <div class="feature-card glass-effect">
            <div class="feature-icon bg-teal"><i class="fa-solid fa-palette"></i></div>
            <h3>Warna Kustom</h3>
            <p>Kelompokkan catatan berdasarkan kategori warna yang cerah dan memanjakan mata Anda.</p>
        </div>
        
        <!-- Feature 4 -->
        <div class="feature-card glass-effect">
            <div class="feature-icon bg-amber"><i class="fa-solid fa-shield-halved"></i></div>
            <h3>Keamanan Terjamin</h3>
            <p>Data Anda diamankan dengan teknologi enkripsi kata sandi termutakhir dan anti SQL injection.</p>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
