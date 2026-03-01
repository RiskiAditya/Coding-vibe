<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Register - Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #92400e 0%, #a16207 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-container {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(146, 64, 14, 0.3);
            padding: 2.5rem 2rem;
            max-width: 450px;
            width: 100%;
            border: 3px solid #92400e;
            animation: slideIn 0.8s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-header .logo {
            font-size: 3rem;
            color: #92400e;
            margin-bottom: 1rem;
        }

        .auth-header h2 {
            color: #92400e;
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-size: 1.8rem;
        }

        .auth-header p {
            color: #6b7280;
            font-size: 0.95rem;
        }

        .tab-buttons {
            display: flex;
            background: #f8f9fa;
            border-radius: 12px;
            padding: 4px;
            margin-bottom: 2rem;
            position: relative;
        }

        .tab-btn {
            flex: 1;
            background: none;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
            color: #6b7280;
            transition: all 0.3s ease;
            position: relative;
            z-index: 2;
        }

        .tab-btn.active {
            color: #fef3c7;
            background: #92400e;
            box-shadow: 0 4px 12px rgba(146, 64, 14, 0.3);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fafafa;
        }

        .form-control:focus {
            outline: none;
            border-color: #92400e;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(146, 64, 14, 0.1);
        }

        .form-control::placeholder {
            color: #9ca3af;
        }

        .password-input {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #92400e;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .password-toggle:hover {
            background: rgba(146, 64, 14, 0.1);
        }

        .auth-btn {
            width: 100%;
            background: linear-gradient(135deg, #92400e 0%, #a16207 100%);
            color: #fef3c7;
            border: none;
            border-radius: 12px;
            padding: 14px 20px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(146, 64, 14, 0.3);
        }

        .auth-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(146, 64, 14, 0.4);
        }

        .auth-btn:active {
            transform: translateY(0);
        }

        .divider {
            text-align: center;
            margin: 1.5rem 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e5e7eb;
        }

        .divider span {
            background: #fff;
            padding: 0 1rem;
            color: #6b7280;
            font-size: 0.9rem;
        }

        .back-link {
            text-align: center;
            margin-top: 1.5rem;
        }

        .back-link a {
            color: #92400e;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .back-link a:hover {
            color: #a16207;
            text-decoration: underline;
        }

        .error-message {
            background: #fef2f2;
            color: #dc2626;
            padding: 10px 12px;
            border-radius: 8px;
            border-left: 4px solid #dc2626;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        @media (max-width: 480px) {
            .auth-container {
                padding: 1.5rem 1rem;
                margin: 10px;
            }

            .auth-header h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-header">
            <div class="logo">
                <i class="fas fa-book-open"></i>
            </div>
            <h2 id="formTitle">Masuk ke Akun</h2>
            <p>Akses koleksi buku perpustakaan kami</p>
        </div>

        <div class="tab-buttons">
            <button id="loginTab" class="tab-btn active" onclick="showForm('login')">Masuk</button>
            <button id="registerTab" class="tab-btn" onclick="showForm('register')">Daftar</button>
        </div>

        @if($errors->any())
            <div class="error-message">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="loginForm" action="{{ route('login.submit') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="loginRole" class="form-label">Peran</label>
                <select name="role" id="loginRole" class="form-control" required>
                    <option value="">Pilih Peran</option>
                    <option value="admin">Administrator</option>
                    <option value="member">Anggota</option>
                </select>
            </div>

            <div class="form-group">
                <label for="loginUsername" class="form-label">Nama Pengguna</label>
                <input type="text" name="username" id="loginUsername" class="form-control" placeholder="Masukkan nama pengguna (3-20 karakter, huruf/angka/-_)" required pattern="^[a-zA-Z][a-zA-Z0-9_-]*$" minlength="3" maxlength="20">
                <small class="text-muted">3-20 karakter, dimulai dengan huruf, hanya huruf, angka, -, _</small>
            </div>

            <div class="form-group">
                <label for="loginPassword" class="form-label">Kata Sandi</label>
                <div class="password-input">
                    <input type="password" name="password" id="loginPassword" class="form-control" placeholder="Masukkan kata sandi (8-20 karakter)" required minlength="8" maxlength="20">
                    <button type="button" class="password-toggle" onclick="togglePassword('loginPassword')" title="Tampilkan/Sembunyikan Kata Sandi">
                        <i class="fas fa-eye" id="loginEyeIcon"></i>
                    </button>
                </div>
                <small class="text-muted">8-20 karakter</small>
            </div>

            <button type="submit" class="auth-btn">
                <i class="fas fa-sign-in-alt me-2"></i>Masuk
            </button>
        </form>

        <form id="registerForm" action="{{ route('register.submit') }}" method="POST" style="display: none;">
            @csrf
            <div class="form-group">
                <label for="registerRole" class="form-label">Peran</label>
                <select name="role" id="registerRole" class="form-control" required>
                    <option value="">Pilih Peran</option>
                    <option value="admin">Administrator</option>
                    <option value="member">Anggota</option>
                </select>
            </div>

            <div class="form-group">
                <label for="registerUsername" class="form-label">Nama Pengguna</label>
                <input type="text" name="username" id="registerUsername" class="form-control" placeholder="Masukkan nama pengguna" required>
            </div>

            <div class="form-group">
                <label for="registerEmail" class="form-label">Email</label>
                <input type="email" name="email" id="registerEmail" class="form-control" placeholder="Masukkan alamat email" required>
            </div>

            <div class="form-group">
                <label for="registerPassword" class="form-label">Kata Sandi</label>
                <div class="password-input">
                    <input type="password" name="password" id="registerPassword" class="form-control" placeholder="Masukkan kata sandi" required>
                    <button type="button" class="password-toggle" onclick="togglePassword('registerPassword')" title="Tampilkan/Sembunyikan Kata Sandi">
                        <i class="fas fa-eye" id="registerEyeIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="auth-btn">
                <i class="fas fa-user-plus me-2"></i>Daftar Akun
            </button>
        </form>

        <div class="back-link">
            <a href="{{ route('home') }}">
                <i class="fas fa-arrow-left me-1"></i>Kembali ke Beranda
            </a>
        </div>
    </div>

    <script>
        function showForm(type) {
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');
            const loginTab = document.getElementById('loginTab');
            const registerTab = document.getElementById('registerTab');
            const formTitle = document.getElementById('formTitle');

            if (type === 'login') {
                loginForm.style.display = 'block';
                registerForm.style.display = 'none';
                loginTab.classList.add('active');
                registerTab.classList.remove('active');
                formTitle.textContent = 'Masuk ke Akun';
            } else {
                loginForm.style.display = 'none';
                registerForm.style.display = 'block';
                loginTab.classList.remove('active');
                registerTab.classList.add('active');
                formTitle.textContent = 'Buat Akun Baru';
            }
        }

        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const eyeIcon = document.getElementById(inputId === 'loginPassword' ? 'loginEyeIcon' : 'registerEyeIcon');

            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.className = 'fas fa-eye-slash';
            } else {
                input.type = 'password';
                eyeIcon.className = 'fas fa-eye';
            }
        }

        // Real-time validation
        function validateUsername(input) {
            const value = input.value;
            const pattern = /^[a-zA-Z][a-zA-Z0-9_-]*$/;
            const isValid = value.length >= 3 && value.length <= 20 && pattern.test(value);

            input.style.borderColor = isValid ? '#10b981' : (value.length > 0 ? '#ef4444' : '#e5e7eb');
            return isValid;
        }

        function validatePassword(input) {
            const value = input.value;
            const isValid = value.length >= 8 && value.length <= 20;

            input.style.borderColor = isValid ? '#10b981' : (value.length > 0 ? '#ef4444' : '#e5e7eb');
            return isValid;
        }

        // Add validation listeners
        document.getElementById('loginUsername').addEventListener('input', function() {
            validateUsername(this);
        });

        document.getElementById('loginPassword').addEventListener('input', function() {
            validatePassword(this);
        });

        document.getElementById('registerUsername').addEventListener('input', function() {
            validateUsername(this);
        });

        document.getElementById('registerPassword').addEventListener('input', function() {
            validatePassword(this);
        });

        // Auto-focus first input on form switch
        document.getElementById('loginTab').addEventListener('click', function() {
            setTimeout(() => document.getElementById('loginRole').focus(), 100);
        });

        document.getElementById('registerTab').addEventListener('click', function() {
            setTimeout(() => document.getElementById('registerRole').focus(), 100);
        });
    </script>
</body>
</html>
