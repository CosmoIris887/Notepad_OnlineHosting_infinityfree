<?php
/**
 * Template Header Aplikasi Notes App
 */

// Memulai session jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Menentukan halaman aktif untuk navigasi
$current_page = basename($_SERVER['PHP_SELF']);

// Memeriksa autentikasi untuk halaman dashboard
if ($current_page === 'dashboard.php' && !isset($_SESSION['user_id'])) {
    $_SESSION['error'] = 'Silakan login terlebih dahulu untuk mengakses dashboard!';
    header('Location: login.php');
    exit;
}

// Memeriksa jika user sudah login tapi mencoba mengakses halaman auth
if (($current_page === 'login.php' || $current_page === 'register.php') && isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Aplikasi Catatan Modern (Notes App) berbasis web menggunakan PHP Native, MySQL, dan CSS Glassmorphism yang responsif.">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' - Antigravity Notes' : 'Antigravity Notes - Simpan Ide-Ide Cemerlangmu'; ?></title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome CDN untuk Icon Modern -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Custom CSS (Premium UI) -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-gradient-radial">
    <div class="app-container">
        
        <!-- Navigation Header -->
        <header class="main-header glass-effect">
            <div class="nav-container">
                <a href="index.php" class="logo">
                    <span class="logo-icon"><i class="fa-solid fa-note-sticky"></i></span>
                    <span class="logo-text">Antigravity<span class="text-accent">Notes</span></span>
                </a>
                
                <nav class="nav-menu" id="navMenu">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="nav-user-info">
                            <span class="welcome-text">Halo, <strong class="username-display"><?php echo htmlspecialchars($_SESSION['username']); ?></strong> <i class="fa-regular fa-face-smile text-accent"></i></span>
                        </div>
                        <a href="dashboard.php" class="nav-link <?php echo $current_page === 'dashboard.php' ? 'active' : ''; ?>">
                            <i class="fa-solid fa-house-user"></i> Dashboard
                        </a>
                        <a href="actions/auth_action.php?action=logout" class="nav-btn btn-logout">
                            <i class="fa-solid fa-right-from-bracket"></i> Keluar
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="nav-link <?php echo $current_page === 'login.php' ? 'active' : ''; ?>">
                            Masuk
                        </a>
                        <a href="register.php" class="nav-btn btn-primary">
                            Daftar Sekarang
                        </a>
                    <?php endif; ?>
                </nav>
                
                <!-- Burger menu untuk mobile -->
                <button class="menu-toggle" id="menuToggle" aria-label="Toggle Menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="main-content">
