<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PERPUSTAKAAN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
            box-shadow: 0 0 0 3px rgba(139, 94, 60, 0.1);
            border-color: #8B5E3C;
        }
        .btn-primary-custom {
            background-color: #8B5E3C;
        }
        .btn-primary-custom:hover {
            background-color: #6B4428;
        }
        .tab-active {
            border-bottom: 3px solid #8B5E3C;
            color: #8B5E3C;
        }
        .tab-inactive {
            border-bottom: 3px solid transparent;
            color: #999;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-8">
                    <div class="flex-shrink-0">
                        <h1 class="text-xl font-bold">PERPUSTAKAAN</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('home') }}" class="text-white hover:text-gray-300 px-3 py-2 rounded-md text-sm font-medium bg-gray-700">Beranda</a>
                        <a href="{{ route('buku') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Buku</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Login</a>
                    @endif
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">Register</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="min-h-screen grid grid-cols-1 md:grid-cols-2">
        <!-- Left Side - Text -->
        <div class="bg-[#8B5E3C] flex items-center justify-center p-8">
            <div class="max-w-lg text-white">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    Selamat Datang di Perpustakaan
                </h1>
                <h2 class="text-xl md:text-2xl mb-6 text-gray-200">
                    Jelajahi Pengetahuan Tradisional dan Modern
                </h2>
                <p class="text-lg mb-8 leading-relaxed">
                    Nikmati koleksi buku beragam yang dikurasi dengan cermat dari berbagai disiplin ilmu. Dari sastra klasik hingga publikasi terkini, semua tersedia untuk memperluas wawasan Anda dalam lingkungan yang nyaman dan tenang.
                </p>
                <a href="#buku" class="inline-block bg-white text-[#8B5E3C] px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300">
                    Mulai membaca →
                </a>
            </div>
        </div>

        <!-- Right Side - Image -->
        <div class="relative">
            <img
                src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80"
                alt="Library Bookshelf"
                class="w-full h-full object-cover min-h-screen"
            >
        </div>
    </div>

    <!-- Featured Books Section -->
    <div id="buku" class="py-16 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Koleksi Buku Terbaru</h2>
                <p class="text-lg text-gray-600">Temukan buku-buku menarik yang siap memperluas wawasan Anda</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
                    $featuredBooks = [
                        [
                            'title' => 'Sejarah Peradaban Islam',
                            'author' => 'Dr. Ahmad Rahman',
                            'description' => 'Buku komprehensif tentang perkembangan peradaban Islam dari masa ke masa',
                            'creation_date' => '10/08/2023',
                            'release_date' => '15/12/2023',
                            'cover' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'
                        ],
                        [
                            'title' => 'Teknologi Modern dan AI',
                            'author' => 'Prof. Siti Nurhaliza',
                            'description' => 'Panduan lengkap tentang kecerdasan buatan dan dampaknya pada kehidupan modern',
                            'creation_date' => '15/07/2023',
                            'release_date' => '22/11/2023',
                            'cover' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'
                        ],
                        [
                            'title' => 'Sastra Indonesia Klasik',
                            'author' => 'Dra. Maya Sari',
                            'description' => 'Kumpulan karya sastra Indonesia klasik dengan analisis mendalam',
                            'creation_date' => '20/05/2023',
                            'release_date' => '08/10/2023',
                            'cover' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'
                        ]
                    ];
                @endphp

                @foreach($featuredBooks as $book)
                    @include('components.book-card', ['book' => $book, 'isSampleView' => true])
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('buku', ['sample' => 1]) }}" class="inline-block bg-[#8B5E3C] text-white px-8 py-3 rounded-lg font-semibold hover:bg-[#6B4428] transition duration-300">
                    Lihat Semua Buku →
                </a>
            </div>
        </div>
    </div>

    <!-- Auth Modal -->
    <div id="authModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 modal-backdrop">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md modal-content mx-4">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-[#8B5E3C] to-[#6B4428] text-white p-6 rounded-t-2xl">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold">
                        <i class="fas fa-book-open me-2"></i>Perpustakaan
                    </h2>
                    <button onclick="closeAuthModal()" class="text-white hover:text-gray-200 text-2xl">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <p class="text-sm text-gray-100 mt-2">Bergabunglah dengan jutaan pembaca</p>
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
                                <i class="fas fa-envelope me-2 text-[#8B5E3C]"></i>Email
                            </label>
                            <input type="email" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus outline-none transition-all duration-300" placeholder="nama@email.com" required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-lock me-2 text-[#8B5E3C]"></i>Password
                            </label>
                            <input type="password" name="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus outline-none transition-all duration-300" placeholder="Masukkan password" required>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="remember" id="remember" class="w-4 h-4">
                            <label for="remember" class="text-sm text-gray-600">Ingat saya</label>
                        </div>

                        <button type="submit" class="w-full bg-gradient-to-r from-[#8B5E3C] to-[#6B4428] text-white font-semibold py-3 rounded-lg hover:shadow-lg transition duration-300 transform hover:scale-105">
                            <i class="fas fa-sign-in-alt me-2"></i>Masuk Sekarang
                        </button>
                    </form>

                    <p class="text-center text-sm text-gray-600 mt-4">
                        Belum punya akun? 
                        <button onclick="switchTab('register')" class="text-[#8B5E3C] font-semibold hover:underline">Daftar di sini</button>
                    </p>
                </div>

                <!-- Register Form -->
                <div id="registerForm" class="hidden">
                    <form action="{{ route('register') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user me-2 text-[#8B5E3C]"></i>Nama Lengkap
                            </label>
                            <input type="text" name="name" class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus outline-none transition-all duration-300" placeholder="Nama Anda" required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-envelope me-2 text-[#8B5E3C]"></i>Email
                            </label>
                            <input type="email" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus outline-none transition-all duration-300" placeholder="nama@email.com" required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-lock me-2 text-[#8B5E3C]"></i>Password
                            </label>
                            <input type="password" name="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus outline-none transition-all duration-300" placeholder="Min. 8 karakter" required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-lock me-2 text-[#8B5E3C]"></i>Konfirmasi Password
                            </label>
                            <input type="password" name="password_confirmation" class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus outline-none transition-all duration-300" placeholder="Ulangi password" required>
                        </div>

                        <div class="flex items-start gap-2">
                            <input type="checkbox" name="agree" id="agree" class="w-4 h-4 mt-1" required>
                            <label for="agree" class="text-sm text-gray-600">Saya setuju dengan syarat dan ketentuan</label>
                        </div>

                        <button type="submit" class="w-full bg-gradient-to-r from-[#8B5E3C] to-[#6B4428] text-white font-semibold py-3 rounded-lg hover:shadow-lg transition duration-300 transform hover:scale-105">
                            <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                        </button>
                    </form>

                    <p class="text-center text-sm text-gray-600 mt-4">
                        Sudah punya akun? 
                        <button onclick="switchTab('login')" class="text-[#8B5E3C] font-semibold hover:underline">Masuk di sini</button>
                    </p>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 px-8 py-4 rounded-b-2xl border-t text-center text-sm text-gray-600">
                <i class="fas fa-shield-alt me-2 text-green-500"></i>Data Anda aman dan terenkripsi
            </div>
        </div>
    </div>

    <!-- JavaScript untuk Modal -->
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
    </script>
</body>
</html>