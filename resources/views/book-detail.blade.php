<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $book->title }} - {{ config('app.name', 'Aplikasi Perpustakaan') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #92400e;">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}" style="color: #fef3c7;">
                <i class="fas fa-book me-2"></i>Aplikasi Perpustakaan
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('buku') }}">Daftar Buku</a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link text-white">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Back Button -->
    <div class="container mt-3 mb-4">
        <a href="{{ url('/semua-buku') }}" class="btn" style="background-color: #92400e; color: #fef3c7; border: none;">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Semua Buku
        </a>
    </div>

    <!-- Book Detail Section -->
    <div class="container py-5">
        <div class="row">
            <!-- Book Cover -->
            <div class="col-lg-4 mb-5">
                <div class="card shadow-lg rounded-lg" style="border: 3px solid #92400e;">
                    @php
                        $imageUrl = $book->image ? asset('storage/books/' . $book->image) : null;
                        if (!$book->image || !\Illuminate\Support\Facades\Storage::disk('public')->exists('books/' . $book->image)) {
                            // Use default image based on category or title
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
                    <img src="{{ $imageUrl }}" alt="{{ $book->title }}" class="card-img-top rounded-top" style="height: 400px; object-fit: cover;">
                    <div class="card-body">
                        @if($book->available_quantity > 0)
                            <span class="badge bg-success mb-3">Tersedia</span>
                        @else
                            <span class="badge bg-danger mb-3">Tidak Tersedia</span>
                        @endif
                        <p class="text-muted mb-2">
                            <strong>Stok:</strong> {{ $book->available_quantity }}/{{ $book->quantity }} buku
                        </p>
                        
                        <div class="d-grid gap-2 mb-2">
                            <a href="{{ route('books.read', $book->id) }}" class="btn btn-info">
                                <i class="fas fa-book-open me-2"></i>Baca Sekarang
                            </a>
                            @if($book->available_quantity > 0)
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#borrowModal">
                                    <i class="fas fa-bookmark me-2"></i>Pinjam Buku
                                </button>
                            @else
                                <button type="button" class="btn btn-secondary" disabled>
                                    <i class="fas fa-times me-2"></i>Stok Habis
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Book Information -->
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-3">{{ $book->title }}</h1>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Pengarang</h6>
                        <p class="fs-5"><strong>{{ $book->author }}</strong></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">ISBN</h6>
                        <p class="fs-5"><strong>{{ $book->isbn }}</strong></p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Penerbit</h6>
                        <p class="fs-5">{{ $book->publisher ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Tahun Terbit</h6>
                        <p class="fs-5">{{ $book->publication_year ?? '-' }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Kategori</h6>
                        <p class="fs-5">
                            <span class="badge bg-primary">{{ $book->category?->name ?? 'Tanpa Kategori' }}</span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Status</h6>
                        <p class="fs-5">
                            @if($book->featured)
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-star me-1"></i>Buku Unggulan
                                </span>
                            @else
                                <span class="badge bg-secondary">Buku Biasa</span>
                            @endif
                        </p>
                    </div>
                </div>

                <hr class="my-5">

                <div>
                    <h4 class="mb-3">Deskripsi Buku</h4>
                    <p class="fs-5 lh-lg" style="text-align: justify;">
                        {{ $book->description ?? 'Tidak ada deskripsi tersedia.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Borrow Modal -->
    <div class="modal fade" id="borrowModal" tabindex="-1" aria-labelledby="borrowModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #92400e;">
                    <h5 class="modal-title text-white" id="borrowModalLabel">
                        <i class="fas fa-bookmark me-2"></i>Pinjam Buku: {{ $book->title }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('borrowing.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="book_id" value="{{ $book->id }}">

                        <div class="mb-3">
                            <label for="borrow_date" class="form-label fw-bold">
                                <i class="fas fa-calendar me-2" style="color: #92400e;"></i>Tanggal Peminjaman
                            </label>
                            <input type="date" class="form-control" id="borrow_date" name="borrow_date" value="{{ date('Y-m-d') }}" required readonly>
                            <small class="text-muted">Tanggal peminjaman otomatis diisi hari ini</small>
                        </div>

                        <div class="mb-3">
                            <label for="due_date" class="form-label fw-bold">
                                <i class="fas fa-calendar-check me-2" style="color: #92400e;"></i>Tanggal Pengembalian
                            </label>
                            <input type="date" class="form-control" id="due_date" name="due_date" required>
                            <small class="text-muted">Pilih tanggal kapan Anda akan mengembalikan buku</small>
                        </div>

                        <div class="alert alert-info" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Informasi Peminjaman:</strong>
                            <ul class="mt-2 mb-0">
                                <li>Durasi peminjaman maksimal: 7 hari</li>
                                <li>Jika terlambat, Anda akan dikenakan denda</li>
                                <li>Pastikan mengembalikan buku dalam kondisi baik</li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" style="background-color: #92400e; border: none;">
                            <i class="fas fa-check me-2"></i>Pinjam Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Set minimum due date to today
        document.addEventListener('DOMContentLoaded', function() {
            const borrowDateInput = document.getElementById('borrow_date');
            const dueDateInput = document.getElementById('due_date');
            const today = new Date().toISOString().split('T')[0];
            
            borrowDateInput.value = today;
            dueDateInput.min = today;
        });
    </script>
</body>
</html>
