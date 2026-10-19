/**
 * Antigravity Notes - Script Validasi Form Autentikasi
 * Mengelola interaksi form login dan register, eye toggle password, dan validasi client-side.
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // --- 1. EYE TOGGLE PASSWORD VISIBILITY ---
    const togglePasswordButtons = document.querySelectorAll('.toggle-password');
    togglePasswordButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Temukan input password terkait (di dalam wrapper yang sama)
            const passwordInput = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // Helper: Validasi format email
    function isValidEmail(email) {
        const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return re.test(String(email).toLowerCase());
    }

    // Helper: Reset feedback error
    function resetErrors(form) {
        const inputs = form.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.classList.remove('form-control', 'is-invalid');
        });
        
        const feedbacks = form.querySelectorAll('.invalid-feedback');
        feedbacks.forEach(feedback => {
            feedback.textContent = '';
            feedback.style.display = 'none';
        });
    }

    // Helper: Set feedback error
    function showError(input, errorElementId, message) {
        input.classList.add('is-invalid');
        const errorElement = document.getElementById(errorElementId);
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.style.display = 'block';
        }
    }

    // --- 2. VALIDASI FORM REGISTRASI ---
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            resetErrors(registerForm);
            
            const usernameInput = document.getElementById('username');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('confirm_password');
            
            let hasError = false;
            
            // Validasi Username
            const usernameVal = usernameInput.value.trim();
            if (usernameVal === '') {
                showError(usernameInput, 'usernameError', 'Username wajib diisi!');
                hasError = true;
            } else if (usernameVal.length < 3 || usernameVal.length > 20) {
                showError(usernameInput, 'usernameError', 'Username harus terdiri dari 3 - 20 karakter!');
                hasError = true;
            } else if (!/^[a-zA-Z0-9_]+$/.test(usernameVal)) {
                showError(usernameInput, 'usernameError', 'Username hanya boleh berisi huruf, angka, dan underscore (_)!');
                hasError = true;
            }
            
            // Validasi Email
            const emailVal = emailInput.value.trim();
            if (emailVal === '') {
                showError(emailInput, 'emailError', 'Alamat email wajib diisi!');
                hasError = true;
            } else if (!isValidEmail(emailVal)) {
                showError(emailInput, 'emailError', 'Format email tidak valid (contoh: user@domain.com)!');
                hasError = true;
            }
            
            // Validasi Password
            const passwordVal = passwordInput.value;
            if (passwordVal === '') {
                showError(passwordInput, 'passwordError', 'Kata sandi wajib diisi!');
                hasError = true;
            } else if (passwordVal.length < 6) {
                showError(passwordInput, 'passwordError', 'Kata sandi minimal harus 6 karakter!');
                hasError = true;
            }
            
            // Validasi Konfirmasi Password
            const confirmPasswordVal = confirmPasswordInput.value;
            if (confirmPasswordVal === '') {
                showError(confirmPasswordInput, 'confirmPasswordError', 'Konfirmasi kata sandi wajib diisi!');
                hasError = true;
            } else if (passwordVal !== confirmPasswordVal) {
                showError(confirmPasswordInput, 'confirmPasswordError', 'Konfirmasi kata sandi tidak cocok!');
                hasError = true;
            }
            
            if (hasError) {
                e.preventDefault(); // Batalkan pengiriman form
            }
        });
    }

    // --- 3. VALIDASI FORM LOGIN ---
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            resetErrors(loginForm);
            
            const identifierInput = document.getElementById('identifier');
            const passwordInput = document.getElementById('password');
            
            let hasError = false;
            
            // Validasi Identifier (Username atau Email)
            const identifierVal = identifierInput.value.trim();
            if (identifierVal === '') {
                showError(identifierInput, 'identifierError', 'Username atau Email wajib diisi!');
                hasError = true;
            }
            
            // Validasi Password
            const passwordVal = passwordInput.value;
            if (passwordVal === '') {
                showError(passwordInput, 'passwordError', 'Kata sandi wajib diisi!');
                hasError = true;
            }
            
            if (hasError) {
                e.preventDefault(); // Batalkan pengiriman form
            }
        });
    }
});
