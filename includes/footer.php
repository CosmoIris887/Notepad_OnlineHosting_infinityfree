        </main> <!-- End Main Content -->

        <!-- Footer -->
        <footer class="main-footer glass-effect">
            <div class="footer-container">
                <p>&copy; <?php echo date('Y'); ?> <strong>Antigravity Notes</strong>. Dibuat dengan <i class="fa-solid fa-heart text-danger"></i> untuk produktivitas Anda.</p>
                <div class="footer-links">
                    <span class="badge-tech"><i class="fa-brands fa-php"></i> PHP Native</span>
                    <span class="badge-tech"><i class="fa-solid fa-database"></i> MySQL</span>
                    <span class="badge-tech"><i class="fa-brands fa-js"></i> JavaScript</span>
                </div>
            </div>
        </footer>
    </div> <!-- End App Container -->

    <!-- Script Global untuk Mobile Menu Toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menuToggle');
            const navMenu = document.getElementById('navMenu');
            
            if (menuToggle && navMenu) {
                menuToggle.addEventListener('click', function() {
                    navMenu.classList.toggle('active');
                    
                    // Ganti icon burger ke close
                    const icon = menuToggle.querySelector('i');
                    if (navMenu.classList.contains('active')) {
                        icon.classList.remove('fa-bars');
                        icon.classList.add('fa-xmark');
                    } else {
                        icon.classList.remove('fa-xmark');
                        icon.classList.add('fa-bars');
                    }
                });
            }
        });
    </script>

    <!-- Memuat Script Kustom Sesuai Halaman -->
    <?php
    $current_page = basename($_SERVER['PHP_SELF']);
    if ($current_page === 'login.php' || $current_page === 'register.php') {
        echo '<script src="js/auth.js"></script>';
    } elseif ($current_page === 'dashboard.php') {
        echo '<script src="js/main.js"></script>';
    }
    ?>
</body>
</html>
