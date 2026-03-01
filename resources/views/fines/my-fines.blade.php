<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Denda Saya - {{ config('app.name', 'Aplikasi Perpustakaan') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .fine-card {
            border-left: 5px solid #dc3545;
            transition: transform 0.2s;
        }
        .fine-card:hover {
            transform: translateX(5px);
        }
        .fine-card.paid {
            border-left-color: #28a745;
            opacity: 0.8;
        }
        .fine-amount {
            font-size: 1.5rem;
            font-weight: bold;
            color: #dc3545;
        }
        .fine-card.paid .fine-amount {
            color: #28a745;
        }
        .stats-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        .stats-box h6 {
            margin-bottom: 5px;
            opacity: 0.9;
        }
        .stats-box .amount {
            font-size: 2rem;
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark gradient-bg shadow-lg">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <i class="fas fa-book-open me-2"></i>
                Perpustakaan Digital
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
                        <a class="nav-link" href="{{ route('buku') }}">
                            <i class="fas fa-book me-1"></i>Buku
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('fines.my-fines') }}">
                            <i class="fas fa-money-bill-wave me-1"></i>Denda Saya
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

    <div class="container mt-5">
        <!-- Header -->
        <div class="mb-5 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="display-4 fw-bold mb-2">
                    <i class="fas fa-money-bill-wave me-3" style="color: #dc3545;"></i>
                    Denda Saya
                </h1>
                <p class="text-muted">Kelola dan bayar denda peminjaman buku Anda</p>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Statistics -->
        <div class="row mb-5">
            <div class="col-md-4 mb-3">
                <div class="stats-box">
                    <h6><i class="fas fa-hourglass-end me-2"></i>Denda Belum Dibayar</h6>
                    <div class="amount">Rp {{ number_format($totalPending, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stats-box" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                    <h6><i class="fas fa-check-circle me-2"></i>Denda Sudah Dibayar</h6>
                    <div class="amount">Rp {{ number_format($totalPaid, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stats-box" style="background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);">
                    <h6><i class="fas fa-list-check me-2"></i>Total Denda</h6>
                    <div class="amount">Rp {{ number_format($totalPending + $totalPaid, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <!-- Fines List -->
        @if($fines->count() > 0)
            <div class="row">
                @foreach($fines as $fine)
                    <div class="col-md-6 mb-4">
                        <div class="card fine-card {{ $fine->status === 'paid' ? 'paid' : '' }}">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col">
                                        <h6 class="card-subtitle mb-1 text-muted">
                                            <i class="fas fa-book me-1"></i>
                                            {{ $fine->borrowing->book->title }}
                                        </h6>
                                        <small class="text-muted">
                                            Dipinjam: {{ $fine->borrowing->borrow_date->format('d/m/Y') }} - 
                                            Kembali: {{ $fine->borrowing->due_date->format('d/m/Y') }}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        @if($fine->status === 'paid')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Sudah Dibayar
                                            </span>
                                        @elseif($fine->status === 'waiting_confirmation')
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-hourglass-end me-1"></i>Menunggu Konfirmasi
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-exclamation me-1"></i>Belum Dibayar
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <hr>

                                <div class="mb-3">
                                    <h6 class="mb-1">
                                        <i class="fas fa-info-circle me-2"></i>Alasan Denda
                                    </h6>
                                    <p class="text-muted mb-2">{{ $fine->reason }}</p>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-6">
                                        <h6 class="mb-1">Jumlah Denda</h6>
                                        <p class="fine-amount mb-0">Rp {{ number_format($fine->amount, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="col-6 text-end">
                                        <h6 class="mb-1">Batas Pembayaran</h6>
                                        <p class="mb-0">
                                            <small>
                                                {{ $fine->due_date->format('d/m/Y') }}
                                                @if($fine->isOverdue() && $fine->status !== 'paid')
                                                    <br>
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>OVERDUE
                                                    </span>
                                                @endif
                                            </small>
                                        </p>
                                    </div>
                                </div>

                                @if($fine->status !== 'paid')
                                    <form action="{{ route('fines.pay', $fine->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger w-100" {{ $fine->status === 'waiting_confirmation' ? 'disabled' : '' }}>
                                            <i class="fas fa-credit-card me-2"></i>
                                            @if($fine->status === 'waiting_confirmation')
                                                Menunggu Konfirmasi Admin
                                            @else
                                                Bayar Sekarang
                                            @endif
                                        </button>
                                    </form>
                                @else
                                    <div class="alert alert-success mb-0">
                                        <small>
                                            <i class="fas fa-check-circle me-1"></i>
                                            Dibayar pada: {{ $fine->paid_date?->format('d/m/Y') ?? '-' }}
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="card text-center py-5">
                <div class="card-body">
                    <i class="fas fa-smile-wink fa-3x text-success mb-3"></i>
                    <h5 class="card-title">Tidak Ada Denda</h5>
                    <p class="card-text text-muted">
                        Selamat! Anda tidak memiliki denda apapun. Teruskan untuk menjaga catatan yang baik!
                    </p>
                    <a href="{{ route('buku') }}" class="btn btn-primary">
                        <i class="fas fa-book me-1"></i>Jelajahi Buku
                    </a>
                </div>
            </div>
        @endif

    </div>

    <footer class="bg-dark text-white mt-5 py-4">
        <div class="container text-center">
            <p>&copy; 2024 Aplikasi Perpustakaan. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
