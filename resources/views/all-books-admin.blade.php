<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Semua Buku - Admin - {{ config('app.name', 'Aplikasi Perpustakaan') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
            transition: all 0.3s ease;
        }
        .book-image {
            height: 200px;
            object-fit: cover;
            background: #f5f5f5;
        }
        .stock-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: bold;
        }
        .card-container {
            position: relative;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark gradient-bg shadow-lg sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="/admin">
                <i class="fas fa-book-open me-2"></i>
                Admin - Perpustakaan Digital
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
                        <a class="nav-link" href="{{ route('admin.books.index') }}">
                            <i class="fas fa-cog me-1"></i>Kelola Buku
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin.all-books') }}">
                            <i class="fas fa-book me-1"></i>Semua Buku
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
    <div class="gradient-bg text-white py-4">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="fas fa-book me-2"></i>Semua Buku Perpustakaan
                    </h1>
                    <p class="mb-0 small">Total {{ $books->total() }} buku | Halaman {{ $books->currentPage() }} dari {{ $books->lastPage() }}</p>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="container-fluid py-4">
        <!-- Search and Filter Section -->
        <div class="row mb-4">
            <div class="col-md-6">
                <form method="GET" action="{{ route('admin.all-books') }}" class="d-flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, pengarang..." class="form-control">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.all-books') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Reset
                        </a>
                    @endif
                </form>
            </div>
            <div class="col-md-3">
                <form method="GET" action="{{ route('admin.all-books') }}" class="d-flex">
                    <select name="category" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="col-md-3">
                <form method="GET" action="{{ route('admin.all-books') }}" class="d-flex">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="unavailable" {{ request('status') == 'unavailable' ? 'selected' : '' }}>Tidak Tersedia</option>
                    </select>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Books Grid -->
        @if($books->count() > 0)
            <div class="row g-4">
                @foreach($books as $book)
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <div class="card card-hover h-100 shadow-sm border-0 card-container">
                            <!-- Stock Badge -->
                            @if($book->available_quantity > 0)
                                <span class="stock-badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>{{ $book->available_quantity }}/{{ $book->quantity }}
                                </span>
                            @else
                                <span class="stock-badge bg-danger">
                                    <i class="fas fa-times-circle me-1"></i>Habis
                                </span>
                            @endif

                            <!-- Book Image -->
                            @php
                                $imageUrl = $book->image ? asset('storage/books/' . $book->image) : null;
                                if (!$book->image || !\Illuminate\Support\Facades\Storage::disk('public')->exists('books/' . $book->image)) {
                                    $defaultImages = [
                                        'Sejarah' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
                                        'Teknologi' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
                                        'Sastra' => 'https://images.unsplash.com/photo-1507842072343-583f20270319?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
                                        'Sains' => 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
                                    ];
                                    $category = $book->category?->name ?? 'Umum';
                                    $imageUrl = $defaultImages[$category] ?? 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80';
                                }
                            @endphp
                            <img src="{{ $imageUrl }}" class="card-img-top book-image" alt="{{ $book->title }}">

                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title fw-bold text-truncate">{{ $book->title }}</h6>
                                <p class="card-text text-muted small mb-1">{{ $book->author }}</p>

                                <div class="mb-2">
                                    <span class="badge bg-info me-1">{{ $book->category?->name ?? 'Lainnya' }}</span>
                                    @if($book->featured)
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-star me-1"></i>Featured
                                        </span>
                                    @endif
                                </div>

                                <p class="card-text small text-muted" style="font-size: 0.85rem;">
                                    <strong>ISBN:</strong> {{ $book->isbn ?? '-' }}<br>
                                    <strong>Penerbit:</strong> {{ $book->publisher ?? '-' }}<br>
                                    <strong>Tahun:</strong> {{ $book->publication_year ?? '-' }}
                                </p>

                                <div class="d-grid gap-2 mt-auto">
                                    <a href="{{ url('/book-detail/' . $book->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>Lihat Detail & Pinjam
                                    </a>
                                    <a href="{{ route('admin.books.edit', $book->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                    <a href="{{ route('books.read', $book->id) }}" class="btn btn-sm btn-info" target="_blank">
                                        <i class="fas fa-book-open me-1"></i>Preview Baca
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-5">
                {{ $books->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="alert alert-info text-center py-5" role="alert">
                <i class="fas fa-search fa-3x mb-3 text-info"></i>
                <h5>Tidak Ada Buku yang Ditemukan</h5>
                <p>Coba ubah filter pencarian atau kategori Anda</p>
                <a href="{{ route('admin.all-books') }}" class="btn btn-primary">
                    <i class="fas fa-redo me-1"></i>Reset Filter
                </a>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white mt-5 py-4">
        <div class="container-fluid text-center">
            <p>&copy; 2024 Aplikasi Perpustakaan. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
