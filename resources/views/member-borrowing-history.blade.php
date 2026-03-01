<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riwayat Peminjaman - {{ config('app.name', 'Aplikasi Perpustakaan') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: bold;
        }
        .status-active {
            background: #d4edda;
            color: #155724;
        }
        .status-return-requested {
            background: #fff3cd;
            color: #856404;
        }
        .status-returned {
            background: #d1ecf1;
            color: #0c5460;
        }
        .status-overdue {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark gradient-bg shadow-lg">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
                <i class="fas fa-book-open me-2"></i>Perpustakaan Digital
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('all-books.member') }}">
                            <i class="fas fa-book me-1"></i>Semua Buku
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('member.borrowing-history') }}">
                            <i class="fas fa-history me-1"></i>Riwayat Peminjaman
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
    <div class="gradient-bg text-white py-8">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-2">
                        <i class="fas fa-history me-2"></i>Riwayat Peminjaman
                    </h1>
                    <p class="mb-0">Kelola dan lihat seluruh riwayat peminjaman buku Anda</p>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="container my-5">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($borrowings->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg">
                    <div class="card-header gradient-bg text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-book me-2"></i>Daftar Peminjaman
                        </h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="fas fa-book me-1"></i>Judul Buku</th>
                                    <th><i class="fas fa-user-edit me-1"></i>Pengarang</th>
                                    <th><i class="fas fa-calendar me-1"></i>Tanggal Pinjam</th>
                                    <th><i class="fas fa-calendar-check me-1"></i>Tenggat Waktu</th>
                                    <th><i class="fas fa-undo me-1"></i>Tanggal Kembali</th>
                                    <th><i class="fas fa-tag me-1"></i>Status</th>
                                    <th><i class="fas fa-cogs me-1"></i>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($borrowings as $borrowing)
                                <tr>
                                    <td>
                                        <strong>{{ $borrowing->book->title }}</strong>
                                    </td>
                                    <td>
                                        {{ $borrowing->book->author }}
                                    </td>
                                    <td>
                                        {{ $borrowing->borrow_date->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        {{ $borrowing->due_date->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        @if($borrowing->return_date)
                                            <span class="text-success">{{ $borrowing->return_date->format('d/m/Y') }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($borrowing->status === 'active')
                                            <span class="status-badge status-active">
                                                <i class="fas fa-check-circle me-1"></i>Aktif
                                            </span>
                                        @elseif($borrowing->status === 'return_requested')
                                            <span class="status-badge status-return-requested">
                                                <i class="fas fa-hourglass-end me-1"></i>Menunggu Konfirmasi
                                            </span>
                                        @elseif($borrowing->status === 'returned')
                                            <span class="status-badge status-returned">
                                                <i class="fas fa-check me-1"></i>Dikembalikan
                                            </span>
                                        @else
                                            <span class="status-badge status-overdue">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $borrowing->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($borrowing->status === 'active')
                                            <form action="{{ route('borrowing.return', $borrowing->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary" title="Kembalikan buku">
                                                    <i class="fas fa-undo"></i> Kembalikan
                                                </button>
                                            </form>
                                        @elseif($borrowing->status === 'return_requested')
                                            <span class="badge bg-warning text-dark">Menunggu Admin</span>
                                        @elseif($borrowing->status === 'returned')
                                            <a href="{{ route('books.read', $borrowing->book->id) }}" class="btn btn-sm btn-info" title="Baca buku ini">
                                                <i class="fas fa-book-open"></i> Baca
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Menampilkan {{ $borrowings->count() }} dari {{ $borrowings->total() }} peminjaman
                            </div>
                            <div>
                                {{ $borrowings->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Legend -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card bg-light border-0">
                    <div class="card-body">
                        <h6 class="card-title mb-3">
                            <i class="fas fa-info-circle me-2"></i>Penjelasan Status
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <span class="status-badge status-active">Aktif</span>
                                    Buku sedang dipinjam dan belum dikembalikan
                                </p>
                                <p class="mb-2">
                                    <span class="status-badge status-return-requested">Menunggu Konfirmasi</span>
                                    Anda sudah mengajukan pengembalian, menunggu admin mengkonfirmasi
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <span class="status-badge status-returned">Dikembalikan</span>
                                    Buku sudah dikembalikan dan diterima oleh admin
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card text-center py-5">
                    <div class="card-body">
                        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                        <h5 class="card-title text-muted">Belum ada riwayat peminjaman</h5>
                        <p class="card-text text-muted mb-3">Anda belum pernah meminjam buku dari perpustakaan kami.</p>
                        <a href="{{ route('all-books.member') }}" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Cari Buku Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white mt-5 py-4">
        <div class="container text-center">
            <p>&copy; 2024 Aplikasi Perpustakaan. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
