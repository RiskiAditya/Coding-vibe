<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Semua Buku - {{ config('app.name', 'Aplikasi Perpustakaan') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #92400e 0%, #a16207 100%);">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="/">
                <i class="fas fa-book-open me-2"></i>Perpustakaan Digital
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('all-books.member') }}">
                            <i class="fas fa-book me-1"></i>Semua Buku
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('fines.my-fines') }}">
                            <i class="fas fa-money-bill-wave me-1"></i>Denda
                        </a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm ms-2">
                                <i class="fas fa-sign-out-alt me-1"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <div style="background: linear-gradient(135deg, #92400e 0%, #a16207 100%);" class="text-white py-8">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-2">
                        <i class="fas fa-book-open me-2"></i>Semua Buku Perpustakaan
                    </h1>
                    <p class="mb-0">Jelajahi koleksi lengkap buku kami yang siap dibaca dan dipinjam</p>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Search and Filter Section -->
            <div class="mb-8">
                <div class="flex flex-col md:flex-row gap-4 justify-center">
                    {{-- Search Form --}}
                    <form method="GET" action="{{ route('all-books.member') }}" class="flex">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, pengarang, atau ISBN..." class="px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent" style="min-width: 250px;">
                        <button type="submit" class="px-6 py-2 bg-amber-600 text-white rounded-r-md hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                            <i class="fas fa-search me-1"></i>Cari
                        </button>
                    </form>

                    {{-- Filter Form --}}
                    <form method="GET" action="{{ route('all-books.member') }}" class="flex gap-2">
                        <select name="category" onchange="this.form.submit()" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500" style="min-width: 200px;">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex items-center justify-between">
                    <div>
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                    <button type="button" class="text-green-700 hover:text-green-900" onclick="this.parentElement.style.display='none';">&times;</button>
                </div>
            @endif

            <!-- Books Grid -->
            @if($books->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($books as $book)
                        @include('components.book-card', ['book' => $book])
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12 flex justify-center">
                    {{ $books->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="text-center py-16">
                    <i class="fas fa-search text-gray-400" style="font-size: 4rem; margin-bottom: 1rem; display: block;"></i>
                    <h5 class="text-2xl font-bold text-gray-600 mb-2">Tidak Ada Buku yang Ditemukan</h5>
                    <p class="text-gray-500 mb-4">Coba ubah filter pencarian atau kategori Anda</p>
                    <a href="{{ route('all-books.member') }}" class="inline-block bg-amber-600 hover:bg-amber-700 text-white font-bold py-2 px-6 rounded-full transition duration-300">
                        <i class="fas fa-redo me-1"></i>Reset Filter
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12 py-6">
        <div class="container-fluid text-center">
            <p class="mb-0">&copy; 2024 Aplikasi Perpustakaan. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
