<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - {{ config('app.name', 'Aplikasi Perpustakaan') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
        }
        .stats-card {
            background: linear-gradient(135deg, #9f0d12ff 0%, #630f0f96 100%);
            border: none;
            border-radius: 15px;
        }
        .admin-card {
            background: linear-gradient(135deg, #ffffffff 0%, #b1a9abff 100%);
            border: none;
            border-radius: 15px;
        }
        .member-card {
            background: linear-gradient(135deg, #ffffffff 0%, #b7b2b1ff 100%);
            border: none;
            border-radius: 15px;
        }
        .welcome-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark gradient-bg shadow-lg">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-book-open me-2"></i>
                Perpustakaan Digital
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('buku', ['sample' => 1]) }}">
                            <i class="fas fa-book me-1"></i>Buku
                        </a>
                    </li>
                    @if(Auth::user()->isAdmin())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-cog me-1"></i>Admin
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('admin.books.index') }}">Kelola Buku</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.members.index') }}">Kelola Anggota</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.borrowings.index') }}">Peminjaman</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.fines.index') }}">Denda</a></li>
                        </ul>
                    </li>
                    @endif
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

    <div class="container-fluid mt-4">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-4 fw-bold mb-2">
                        <i class="fas fa-user-circle me-3"></i>
                        Selamat Datang, {{ Auth::user()->name }}!
                    </h1>
                    <p class="lead mb-0">
                        Anda login sebagai <strong>{{ Auth::user()->isAdmin() ? 'Administrator' : 'Member' }}</strong>
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="d-flex justify-content-end flex-column align-items-end gap-3">
                        <div class="bg-white bg-opacity-10 rounded-pill px-4 py-2 text-dark fw-bold">
                            <i class="fas fa-calendar-alt me-2"></i>
                            {{ date('l, d F Y') }}
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-light fw-bold" style="min-width: 180px;">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row mb-5">
            <div class="col-md-4 mb-4">
                <div class="card stats-card card-hover shadow h-100">
                    <div class="card-body text-center text-white">
                        <div class="mb-3">
                            <i class="fas fa-book fa-3x opacity-75"></i>
                        </div>
                        <h2 class="card-title mb-2">{{ $total_books ?? 0 }}</h2>
                        <h5 class="card-subtitle mb-0">Total Buku</h5>
                        <small class="opacity-75">Koleksi lengkap perpustakaan</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card stats-card card-hover shadow h-100">
                    <div class="card-body text-center text-white">
                        <div class="mb-3">
                            <i class="fas fa-book-reader fa-3x opacity-75"></i>
                        </div>
                        <h2 class="card-title mb-2">{{ $active_loans ?? 0 }}</h2>
                        <h5 class="card-subtitle mb-0">Peminjaman Buku</h5>
                        <small class="opacity-75">Buku yang sedang dipinjam</small>
                        @if(isset($pending_returns) && $pending_returns > 0)
                            <div class="mt-2">
                                <a href="{{ route('admin.borrowings.index') }}?status=return_requested" class="badge bg-warning text-dark">{{ $pending_returns }} menunggu konfirmasi</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card stats-card card-hover shadow h-100">
                    <div class="card-body text-center text-white">
                        <div class="mb-3">
                            <i class="fas fa-money-bill-wave fa-3x opacity-75"></i>
                        </div>
                        <h5 class="card-subtitle mb-2">Denda Pending</h5>
                        <h2 class="card-title mb-0">Rp {{ number_format($pending_fines ?? 0, 0, ',', '.') }}</h2>
                        <small class="opacity-75">Denda yang belum dibayar</small>
                    </div>
                </div>
            </div>
        </div>

        @if(Auth::user()->isAdmin())
        <!-- Admin Quick Actions -->
        <div class="row mb-5">
            <div class="col-12">
                <h3 class="mb-4">
                    <i class="fas fa-tools me-2"></i>Fitur Administrator
                </h3>
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="card admin-card card-hover shadow h-100">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="fas fa-book fa-3x text-primary"></i>
                                </div>
                                <h5 class="card-title">Kelola Buku</h5>
                                <p class="card-text text-muted small">Tambah, edit, hapus koleksi buku</p>
                                <a href="{{ route('admin.books.index') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-arrow-right me-1"></i>Kelola
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card admin-card card-hover shadow h-100">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="fas fa-users fa-3x text-success"></i>
                                </div>
                                <h5 class="card-title">Kelola Anggota</h5>
                                <p class="card-text text-muted small">Kelola data anggota perpustakaan</p>
                                <a href="{{ route('admin.members.index') }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-arrow-right me-1"></i>Kelola
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card admin-card card-hover shadow h-100">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="fas fa-exchange-alt fa-3x text-warning"></i>
                                </div>
                                <h5 class="card-title">Peminjaman</h5>
                                <p class="card-text text-muted small">Pantau dan kelola peminjaman</p>
                                <a href="{{ route('admin.borrowings.index') }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-arrow-right me-1"></i>Kelola
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card admin-card card-hover shadow h-100">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="fas fa-money-bill-wave fa-3x text-danger"></i>
                                </div>
                                <h5 class="card-title">Kelola Denda</h5>
                                <p class="card-text text-muted small">Pantau dan kelola pembayaran denda</p>
                                <a href="{{ route('admin.fines.index') }}" class="btn btn-danger btn-sm">
                                    <i class="fas fa-arrow-right me-1"></i>Kelola
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2"></i>Riwayat Aktivitas Terbaru
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th><i class="fas fa-calendar me-1"></i>Tanggal</th>
                                        <th><i class="fas fa-tag me-1"></i>Aktivitas</th>
                                        <th><i class="fas fa-info-circle me-1"></i>Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recent_activities ?? [] as $activity)
                                    <tr>
                                        <td>{{ $activity['date'] }}</td>
                                        <td>
                                            <span class="badge bg-primary">{{ $activity['type'] }}</span>
                                        </td>
                                        <td>{{ $activity['detail'] }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-2x mb-2"></i>
                                            <br>Belum ada aktivitas terbaru
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reports & Analytics -->
        <div class="row mb-5">
            <div class="col-12">
                <h3 class="mb-4">
                    <i class="fas fa-chart-bar me-2"></i>Laporan & Statistik
                </h3>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card shadow h-100">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-chart-pie me-2"></i>Buku Berdasarkan Kategori
                                </h5>
                            </div>
                            <div class="card-body">
                                <canvas id="categoryChart" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow h-100">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-chart-line me-2"></i>Peminjaman Bulanan
                                </h5>
                            </div>
                            <div class="card-body">
                                <canvas id="borrowingChart" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Member Quick Actions -->
        <div class="row mb-5">
            <div class="col-12">
                <h3 class="mb-4">
                    <i class="fas fa-user me-2"></i>Menu Anggota
                </h3>
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="card member-card card-hover shadow h-100" style="border-top: 4px solid #17a2b8;">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="fas fa-book-open fa-3x" style="color: #17a2b8;"></i>
                                </div>
                                <h5 class="card-title">Baca Digital</h5>
                                <p class="card-text text-muted small">Baca buku online tanpa perlu meminjam</p>
                                <a href="{{ route('all-books.member') }}" class="btn btn-sm" style="background: #17a2b8; color: white;">
                                    <i class="fas fa-book-open me-1"></i>Mulai Baca
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card member-card card-hover shadow h-100">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="fas fa-search fa-3x text-primary"></i>
                                </div>
                                <h5 class="card-title">Cari Buku</h5>
                                <p class="card-text text-muted small">Jelajahi koleksi buku perpustakaan</p>
                                <a href="{{ route('all-books.member') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-search me-1"></i>Cari Buku
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card member-card card-hover shadow h-100">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="fas fa-history fa-3x text-info"></i>
                                </div>
                                <h5 class="card-title">Riwayat Peminjaman</h5>
                                <p class="card-text text-muted small">Lihat riwayat peminjaman Anda</p>
                                <a href="{{ route('member.borrowing-history') }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-history me-1"></i>Lihat Riwayat
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card member-card card-hover shadow h-100">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="fas fa-money-bill-wave fa-3x text-warning"></i>
                                </div>
                                <h5 class="card-title">Denda Saya</h5>
                                <p class="card-text text-muted small">Pantau dan bayar denda peminjaman</p>
                                <a href="{{ route('fines.my-fines') }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-money-bill-wave me-1"></i>Cek Denda
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Member Current Loans -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-book-reader me-2"></i>Buku yang Sedang Dipinjam
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(isset($current_loans) && count($current_loans) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Judul Buku</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Tanggal Kembali</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($current_loans as $loan)
                                    <tr>
                                        <td>{{ $loan->book->title }}</td>
                                        <td>{{ $loan->borrow_date->format('d/m/Y') }}</td>
                                        <td>{{ $loan->due_date->format('d/m/Y') }}</td>
                                    <td>
                                            @if($loan->due_date < now())
                                                <span class="badge bg-danger">Terlambat</span>
                                            @elseif($loan->status === 'return_requested')
                                                <span class="badge bg-warning">Menunggu Konfirmasi</span>
                                            @else
                                                <span class="badge bg-success">Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('borrowing.return', $loan->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-undo me-1"></i>Kembalikan
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-4">
                            <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada buku yang dipinjam</h5>
                            <p class="text-muted">Mulai pinjam buku dari koleksi perpustakaan kami</p>
                            <a href="{{ route('all-books.member') }}" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>Cari Buku
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Featured Books Section -->
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
                        <form method="GET" action="{{ route('dashboard') }}" class="flex">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari buku..." class="px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                            <button type="submit" class="px-6 py-2 bg-amber-600 text-white rounded-r-md hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                                Cari
                            </button>
                        </form>

                        {{-- Filter Form --}}
                        <form method="GET" action="{{ route('dashboard') }}" class="flex gap-2">
                            <select name="category" onchange="this.form.submit()" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500">
                                <option value="">Semua Kategori</option>
                                @if(isset($categories))
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </form>
                    </div>
                </div>

                {{-- Books Grid --}}
                @if(isset($featured_books) && count($featured_books) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($featured_books as $book)
                            @include('components.book-card', ['book' => $book])
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-book fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada buku</h5>
                        <p class="text-muted">Koleksi buku akan segera ditambahkan.</p>
                    </div>
                @endif

                <div class="text-center mt-8">
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.all-books') }}" class="inline-block bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-8 rounded-full transition duration-300 shadow-lg hover:shadow-xl">
                            <i class="fas fa-search me-2"></i>Lihat Semua Buku
                        </a>
                    @else
                        <a href="{{ route('all-books.member') }}" class="inline-block bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-8 rounded-full transition duration-300 shadow-lg hover:shadow-xl">
                            <i class="fas fa-search me-2"></i>Lihat Semua Buku
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>



    <footer class="bg-dark text-white mt-5 py-4">
        <div class="container text-center">
            <p>&copy; 2024 Aplikasi Perpustakaan. All rights reserved.</p>
        </div>
    </footer>