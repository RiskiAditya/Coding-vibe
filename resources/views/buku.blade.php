<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Buku - {{ config('app.name', 'Aplikasi Perpustakaan') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .modal-backdrop {
            animation: fadeIn 0.3s ease-in;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        .modal-content {
            animation: slideUp 0.3s ease-out;
        }
        @keyframes slideUp {
            from {
                transform: translateY(40px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(146, 64, 14, 0.1);
            border-color: #92400e;
        }
        .tab-active {
            border-bottom: 3px solid #92400e;
            color: #92400e;
        }
        .tab-inactive {
            border-bottom: 3px solid transparent;
            color: #999;
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #92400e;">
        <div class="container">
            <a class="navbar-brand" href="#" style="color: #fef3c7;">Aplikasi Perpustakaan</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('buku') }}">Buku</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Peminjaman</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link text-white">Logout</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    {{-- Back Button --}}
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('home') }}" class="btn" style="background-color: #92400e; color: #fef3c7; border: none;">
                    ← Kembali ke Dashboard Utama
                </a>
            </div>
        </div>
    </div>

    {{-- Sample View Banner for Guest Users --}}
    @if($isSampleView ?? false)
    <div class="container mt-3 mb-3">
        <div class="alert alert-info alert-dismissible fade show" role="alert" style="background-color: #d1ecf1; border-color: #bee5eb; color: #0c5460;">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Buku Sampel - Daftar untuk Akses Penuh</strong>
            <p class="mb-2 mt-2">Anda sedang melihat hanya beberapa sampel buku. Silakan <a href="{{ route('login') }}" class="alert-link font-weight-bold">login atau daftar</a> untuk mengakses seluruh koleksi buku dan fitur lengkap.</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif

    {{-- Featured Books Section --}}
    <div id="buku" class="py-16 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Koleksi Buku Terbaru</h2>
                <p class="text-lg text-gray-600">Temukan buku-buku menarik yang siap memperluas wawasan Anda</p>
            </div>

            {{-- Search and Filter Forms --}}
            <div class="mb-8">
                <div class="flex flex-col md:flex-row gap-4 justify-center">
                    {{-- Search Form --}}
                    <form method="GET" action="{{ route('buku') }}" class="flex">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari buku..." class="px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                        <button type="submit" class="px-6 py-2 bg-amber-600 text-white rounded-r-md hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                            Cari
                        </button>
                    </form>

                    {{-- Filter Form --}}
                    <form method="GET" action="{{ route('buku') }}" class="flex gap-2">
                        <select name="category" onchange="this.form.submit()" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            {{-- Books Grid --}}
            @if($books->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($books as $book)
                        @include('components.book-card', ['book' => $book, 'isSampleView' => ($isSampleView ?? false)])
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada buku</h5>
                    <p class="text-muted">Koleksi buku akan segera ditambahkan.</p>
                </div>
            @endif
        </div>
    </div>



    <footer class="bg-dark text-white mt-5 py-4">
        <div class="container text-center">
            <p>&copy; 2024 Aplikasi Perpustakaan. All rights reserved.</p>
        </div>
    </footer>

    <!-- Auth Modal -->
    <div id="authModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 modal-backdrop">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md modal-content mx-4">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r" style="background: linear-gradient(to right, #92400e, #6B3209);">
                <div style="color: white; padding: 1.5rem;">
                    <div class="flex justify-between items-center">
                        <h2 class="text-2xl font-bold">
                            <i class="fas fa-book-open me-2"></i>Perpustakaan
                        </h2>
                        <button onclick="closeAuthModal()" class="text-white hover:text-gray-200 text-2xl">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <p class="text-sm mt-2" style="color: #fef3c7;">Bergabunglah untuk membaca lebih banyak buku</p>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-8">
                <!-- Tab Navigation -->
                <div class="flex gap-4 mb-8 border-b">
                    <button onclick="switchTab('login')" id="loginTab" class="pb-3 font-semibold tab-active transition-all duration-300 flex-1">
                        <i class="fas fa-sign-in-alt me-2"></i>Masuk
                    </button>
                    <button onclick="switchTab('register')" id="registerTab" class="pb-3 font-semibold tab-inactive transition-all duration-300 flex-1">
                        <i class="fas fa-user-plus me-2"></i>Daftar
                    </button>
                </div>

                <!-- Login Form -->
                <div id="loginForm">
                    <form action="{{ route('login') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-envelope me-2" style="color: #92400e;"></i>Email
                            </label>
                            <input type="email" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus outline-none transition-all duration-300" placeholder="nama@email.com" required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-lock me-2" style="color: #92400e;"></i>Password
                            </label>
                            <input type="password" name="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus outline-none transition-all duration-300" placeholder="Masukkan password" required>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="remember" id="remember" class="w-4 h-4">
                            <label for="remember" class="text-sm text-gray-600">Ingat saya</label>
                        </div>

                        <button type="submit" class="w-full text-white font-semibold py-3 rounded-lg hover:shadow-lg transition duration-300 transform hover:scale-105" style="background: linear-gradient(to right, #92400e, #6B3209);">
                            <i class="fas fa-sign-in-alt me-2"></i>Masuk Sekarang
                        </button>
                    </form>

                    <p class="text-center text-sm text-gray-600 mt-4">
                        Belum punya akun? 
                        <button onclick="switchTab('register')" class="font-semibold hover:underline" style="color: #92400e;">Daftar di sini</button>
                    </p>
                </div>

                <!-- Register Form -->
                <div id="registerForm" class="hidden">
                    <form action="{{ route('register') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user me-2" style="color: #92400e;"></i>Nama Lengkap
                            </label>
                            <input type="text" name="name" class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus outline-none transition-all duration-300" placeholder="Nama Anda" required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-envelope me-2" style="color: #92400e;"></i>Email
                            </label>
                            <input type="email" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus outline-none transition-all duration-300" placeholder="nama@email.com" required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-lock me-2" style="color: #92400e;"></i>Password
                            </label>
                            <input type="password" name="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus outline-none transition-all duration-300" placeholder="Min. 8 karakter" required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-lock me-2" style="color: #92400e;"></i>Konfirmasi Password
                            </label>
                            <input type="password" name="password_confirmation" class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus outline-none transition-all duration-300" placeholder="Ulangi password" required>
                        </div>

                        <div class="flex items-start gap-2">
                            <input type="checkbox" name="agree" id="agree" class="w-4 h-4 mt-1" required>
                            <label for="agree" class="text-sm text-gray-600">Saya setuju dengan syarat dan ketentuan</label>
                        </div>

                        <button type="submit" class="w-full text-white font-semibold py-3 rounded-lg hover:shadow-lg transition duration-300 transform hover:scale-105" style="background: linear-gradient(to right, #92400e, #6B3209);">
                            <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                        </button>
                    </form>

                    <p class="text-center text-sm text-gray-600 mt-4">
                        Sudah punya akun? 
                        <button onclick="switchTab('login')" class="font-semibold hover:underline" style="color: #92400e;">Masuk di sini</button>
                    </p>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 px-8 py-4 rounded-b-2xl border-t text-center text-sm text-gray-600">
                <i class="fas fa-shield-alt me-2 text-green-500"></i>Data Anda aman dan terenkripsi
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openAuthModal() {
            document.getElementById('authModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeAuthModal() {
            document.getElementById('authModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function switchTab(tab) {
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');
            const loginTab = document.getElementById('loginTab');
            const registerTab = document.getElementById('registerTab');

            if (tab === 'login') {
                loginForm.classList.remove('hidden');
                registerForm.classList.add('hidden');
                loginTab.classList.add('tab-active');
                loginTab.classList.remove('tab-inactive');
                registerTab.classList.remove('tab-active');
                registerTab.classList.add('tab-inactive');
            } else {
                loginForm.classList.add('hidden');
                registerForm.classList.remove('hidden');
                loginTab.classList.remove('tab-active');
                loginTab.classList.add('tab-inactive');
                registerTab.classList.add('tab-active');
                registerTab.classList.remove('tab-inactive');
            }
        }

        // Close modal when clicking outside
        document.getElementById('authModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAuthModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAuthModal();
            }
        });

        // Search functionality
        document.getElementById('searchInput') && document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const bookItems = document.querySelectorAll('.book-item');

            bookItems.forEach(item => {
                const title = item.querySelector('h3').textContent.toLowerCase();
                const author = item.querySelector('p').textContent.toLowerCase();

                if (title.includes(searchTerm) || author.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Category filter functionality
        document.getElementById('categoryFilter') && document.getElementById('categoryFilter').addEventListener('change', function() {
            const selectedCategory = this.value;
            const bookItems = document.querySelectorAll('.book-item');

            bookItems.forEach(item => {
                const itemCategory = item.getAttribute('data-category');

                if (selectedCategory === '' || itemCategory === selectedCategory) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Load more functionality (placeholder)
        document.getElementById('loadMoreBtn') && document.getElementById('loadMoreBtn').addEventListener('click', function() {
            alert('Fitur muat lebih banyak akan diimplementasikan dengan database');
        });
    </script>
</body>
</html>
