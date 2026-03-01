<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $book->title }} - Baca Digital - {{ config('app.name', 'Aplikasi Perpustakaan') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1a1a1a;
            color: #333;
        }

        .reader-container {
            background: white;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .reader-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .reader-content {
            flex: 1;
            padding: 40px;
            max-width: 900px;
            margin: 0 auto;
            width: 100%;
        }

        .book-info {
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: 30px;
            margin-bottom: 40px;
            padding-bottom: 40px;
            border-bottom: 2px solid #e0e0e0;
        }

        .book-cover {
            width: 200px;
            height: 280px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            background: #f0f0f0;
        }

        .book-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .book-cover-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .book-meta {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .book-title {
            font-size: 2.5rem;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 10px;
        }

        .book-author {
            font-size: 1.2rem;
            color: #667eea;
            margin-bottom: 20px;
        }

        .book-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }

        .detail-item {
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .detail-label {
            font-weight: bold;
            color: #667eea;
            font-size: 0.9rem;
            text-transform: uppercase;
        }

        .detail-value {
            font-size: 1rem;
            color: #333;
            margin-top: 5px;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .action-buttons .btn {
            flex: 1;
            padding: 12px 20px;
            font-weight: bold;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-read {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-read:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            color: white;
            text-decoration: none;
        }

        .btn-borrow {
            background: #28a745;
            color: white;
        }

        .btn-borrow:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(40, 167, 69, 0.3);
            color: white;
            text-decoration: none;
        }

        .btn-back {
            background: #6c757d;
            color: white;
        }

        .btn-back:hover {
            background: #5a6268;
            color: white;
            text-decoration: none;
        }

        .book-description {
            font-size: 1.05rem;
            line-height: 1.8;
            color: #333;
            text-align: justify;
            margin-bottom: 40px;
            padding-bottom: 40px;
            border-bottom: 2px solid #e0e0e0;
        }

        .book-description strong {
            color: #667eea;
        }

        .reading-tips {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            border-left: 5px solid #667eea;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 40px;
        }

        .reading-tips h5 {
            color: #667eea;
            margin-bottom: 10px;
        }

        .reading-tips ul {
            margin: 0;
            padding-left: 20px;
        }

        .reading-tips li {
            margin-bottom: 8px;
            color: #333;
        }

        .full-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            line-height: 1.8;
            font-size: 1.05rem;
            color: #333;
        }

        .content-section {
            margin-bottom: 30px;
            text-align: justify;
        }

        .content-section h3 {
            color: #667eea;
            margin-top: 30px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .reading-progress {
            background: #e0e0e0;
            height: 8px;
            border-radius: 4px;
            margin: 30px 0;
            overflow: hidden;
        }

        .reading-progress-bar {
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            height: 100%;
            width: 0%;
            transition: width 0.3s ease;
        }

        .reader-footer {
            background: #f8f9fa;
            padding: 20px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            margin-top: 40px;
        }

        .reader-footer p {
            margin: 0;
            color: #666;
        }

        @media (max-width: 768px) {
            .book-info {
                grid-template-columns: 1fr;
            }

            .book-cover {
                width: 100%;
                height: 300px;
            }

            .book-title {
                font-size: 1.8rem;
            }

            .reader-content {
                padding: 20px;
            }

            .action-buttons {
                flex-direction: column;
            }
        }

        .badge-status {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
            margin-right: 10px;
        }

        .badge-available {
            background: #d4edda;
            color: #155724;
        }

        .badge-unavailable {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="reader-container">
        <!-- Reader Header -->
        <div class="reader-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col">
                        <a href="{{ route('all-books.member', ['category' => $book->category_id]) }}" class="btn btn-back btn-sm">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Buku
                        </a>
                    </div>
                    <div class="col text-center">
                        <h5 class="mb-0">
                            <i class="fas fa-book-open me-2"></i>Baca Digital - {{ config('app.name', 'Perpustakaan') }}
                        </h5>
                    </div>
                    <div class="col text-end">
                        <a href="{{ route('dashboard') }}" class="btn btn-sm" style="background: white; color: #667eea; font-weight: bold;">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reader Content -->
        <div class="reader-content">
            <!-- Book Header Info -->
            <div class="book-info">
                <!-- Book Cover -->
                <div class="book-cover">
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
                    @if($imageUrl)
                        <img src="{{ $imageUrl }}" alt="{{ $book->title }}">
                    @else
                        <div class="book-cover-placeholder">
                            <i class="fas fa-book fa-5x"></i>
                        </div>
                    @endif
                </div>

                <!-- Book Metadata -->
                <div class="book-meta">
                    <div>
                        <h1 class="book-title">{{ $book->title }}</h1>
                        <p class="book-author">
                            <i class="fas fa-pen me-2"></i>{{ $book->author }}
                        </p>

                        <div class="book-details">
                            <div class="detail-item">
                                <div class="detail-label"><i class="fas fa-barcode me-1"></i>ISBN</div>
                                <div class="detail-value">{{ $book->isbn ?? '-' }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label"><i class="fas fa-building me-1"></i>Penerbit</div>
                                <div class="detail-value">{{ $book->publisher ?? '-' }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label"><i class="fas fa-calendar me-1"></i>Tahun Terbit</div>
                                <div class="detail-value">{{ $book->publication_year ?? '-' }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label"><i class="fas fa-tag me-1"></i>Kategori</div>
                                <div class="detail-value">{{ $book->category?->name ?? 'Tanpa Kategori' }}</div>
                            </div>
                        </div>

                        <div style="margin-top: 20px;">
                            @if($book->available_quantity > 0)
                                <span class="badge-status badge-available">
                                    <i class="fas fa-check-circle me-1"></i>Tersedia ({{ $book->available_quantity }}/{{ $book->quantity }})
                                </span>
                            @else
                                <span class="badge-status badge-unavailable">
                                    <i class="fas fa-times-circle me-1"></i>Tidak Tersedia
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <button type="button" class="btn btn-read" data-bs-toggle="modal" data-bs-target="#readModal">
                            <i class="fas fa-book-open me-2"></i>Baca Sekarang
                        </button>
                        @if($book->available_quantity > 0)
                            <button type="button" class="btn btn-borrow" data-bs-toggle="modal" data-bs-target="#borrowModal">
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

            <!-- Book Description -->
            @if($book->description)
                <div class="book-description">
                    <h3 style="color: #667eea; margin-bottom: 15px;">
                        <i class="fas fa-info-circle me-2"></i>Deskripsi
                    </h3>
                    {{ $book->description }}
                </div>
            @endif

            <!-- Category Filter Section -->
            @if($book->enable_category_filter)
            <div style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%); border-left: 5px solid #667eea; padding: 20px; border-radius: 8px; margin-bottom: 40px;">
                <h5 style="color: #667eea; margin-bottom: 15px;">
                    <i class="fas fa-filter me-2"></i>Filter Kategori Buku
                </h5>
                <form method="GET" action="{{ route('all-books.member') }}" class="row g-2 align-items-end">
                    <div class="col-md-6">
                        <label for="categoryFilter" class="form-label">
                            <i class="fas fa-tag me-1"></i>Pilih Kategori
                        </label>
                        <select class="form-select" id="categoryFilter" name="category" onchange="this.form.submit()">
                            <option value="">Semua Kategori</option>
                            @php
                                $allCategories = \App\Models\Category::all();
                            @endphp
                            @foreach($allCategories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }} ({{ $category->books()->where('available_quantity', '>', 0)->count() }} buku)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('all-books.member') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-redo me-1"></i>Reset Filter
                        </a>
                    </div>
                </form>
            </div>
            @endif

            <!-- Reading Tips -->
            <div class="reading-tips">
                <h5><i class="fas fa-lightbulb me-2"></i>Tips Membaca</h5>
                <ul>
                    <li>Baca dalam lingkungan yang tenang dan nyaman</li>
                    <li>Ambil istirahat setiap 30-45 menit untuk kesehatan mata</li>
                    <li>Gunakan font size yang nyaman untuk dibaca</li>
                    <li>Catat poin-poin penting saat membaca</li>
                </ul>
            </div>

            <!-- Full Book Content -->
            <div class="full-content">
                <h3 class="mb-4" style="color: #667eea;">
                    <i class="fas fa-scroll me-2"></i>Isi Buku
                </h3>

                @if($book->description)
                    <div class="content-section">
                        <h4>Ringkasan</h4>
                        <p>{{ $book->description }}</p>
                    </div>
                @endif

                <div class="content-section">
                    <h4><i class="fas fa-bookmark me-2"></i>Bab 1 - Pendahuluan</h4>
                    <p>
                        {{ $book->title }} adalah sebuah karya literatur yang penting dan bermakna. Buku ini ditulis oleh 
                        <strong>{{ $book->author }}</strong> dan diterbitkan oleh 
                        <strong>{{ $book->publisher ?? 'Penerbit Terpercaya' }}</strong> pada tahun 
                        <strong>{{ $book->publication_year ?? 'tahun terbit' }}</strong>.
                    </p>
                    <p>
                        Dalam buku ini, pembaca akan menemukan wawasan mendalam tentang berbagai aspek kehidupan. 
                        Setiap halaman dirancang untuk memberikan pembelajaran dan hiburan yang seimbang kepada pembaca 
                        dari berbagai latar belakang pendidikan.
                    </p>
                    <p>
                        Kami mengundang Anda untuk memulai perjalanan membaca yang mengesankan dengan buku ini. 
                        Semoga setiap kata dan kalimat dapat memberikan manfaat dan menginspirasi Anda.
                    </p>
                </div>

                <div class="reading-progress">
                    <div class="reading-progress-bar" id="progressBar"></div>
                </div>

                <div class="content-section">
                    <h4><i class="fas fa-lightbulb me-2"></i>Pesan Utama</h4>
                    <p>
                        Pesan utama dari <strong>{{ $book->title }}</strong> adalah pentingnya pemahaman mendalam 
                        terhadap topik yang dibahas. Melalui narasi yang menarik dan bukti-bukti yang solid, 
                        buku ini mengajak pembaca untuk berpikir kritis dan membuat keputusan yang lebih baik.
                    </p>
                </div>

                <div class="content-section">
                    <h4><i class="fas fa-bookmark me-2"></i>Kesimpulan</h4>
                    <p>
                        Terima kasih telah membaca <strong>{{ $book->title }}</strong>. Kami harap buku ini memberikan 
                        nilai tambah dalam pengetahuan dan pemahaman Anda. Jangan ragu untuk membaca ulang bagian-bagian 
                        tertentu yang menurut Anda penting, dan bagikan pembelajaran Anda dengan orang lain.
                    </p>
                </div>
            </div>

            <!-- Reading Footer -->
            <div class="reader-footer">
                <p>
                    <i class="fas fa-book-open me-2"></i>Terima kasih telah membaca digital melalui Perpustakaan Digital kami
                </p>
                <p style="font-size: 0.9rem; margin-top: 10px;">
                    &copy; 2024 {{ config('app.name', 'Perpustakaan Digital') }}. Semua hak dilindungi.
                </p>
            </div>
        </div>
    </div>

    <!-- Reading Modal -->
    <div class="modal fade" id="readModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
                    <h5 class="modal-title">
                        <i class="fas fa-book-open me-2"></i>Mulai Membaca
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Anda tentang memulai membaca:</p>
                    <h6 class="text-primary mb-3"><i class="fas fa-book me-2"></i>{{ $book->title }}</h6>
                    <p class="text-muted mb-3">Pengarang: <strong>{{ $book->author }}</strong></p>
                    <p class="text-muted">
                        Nikmati pengalaman membaca digital tanpa batas. Anda dapat membaca kapan saja dan di mana saja 
                        dengan kenyamanan maksimal.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <button type="button" id="startReadingBtn" class="btn btn-primary" data-bs-dismiss="modal" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                        <i class="fas fa-check me-1"></i>Mulai Membaca
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Borrow Modal -->
    <div class="modal fade" id="borrowModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border: none;">
                    <h5 class="modal-title">
                        <i class="fas fa-bookmark me-2"></i>Pinjam Buku
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">Informasi Peminjaman:</p>
                    <div class="alert alert-info" role="alert">
                        <strong>{{ $book->title }}</strong><br>
                        Pengarang: {{ $book->author }}
                    </div>

                    <form id="borrowForm" action="{{ route('borrowing.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="book_id" value="{{ $book->id }}">

                        <div class="mb-3">
                            <label for="borrowDate" class="form-label">
                                <i class="fas fa-calendar me-1"></i>Tanggal Peminjaman
                            </label>
                            <input type="date" class="form-control" id="borrowDate" name="borrow_date" 
                                value="{{ date('Y-m-d') }}" readonly required>
                        </div>

                        <div class="mb-3">
                            <label for="dueDate" class="form-label">
                                <i class="fas fa-calendar-check me-1"></i>Tanggal Target Pengembalian
                            </label>
                            <input type="date" class="form-control" id="dueDate" name="due_date" required
                                min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                        </div>

                        <div class="alert alert-warning" role="alert">
                            <small>
                                <i class="fas fa-info-circle me-1"></i>
                                Durasi peminjaman maksimal 7 hari. Jika melewati batas, akan dikenakan denda Rp 1.000/hari.
                            </small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <button type="button" class="btn" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white;" onclick="document.getElementById('borrowForm').submit();">
                        <i class="fas fa-check me-1"></i>Pinjam Buku
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple reading progress tracking (safely update if element exists)
        window.addEventListener('scroll', function() {
            const progressBar = document.getElementById('progressBar');
            if (!progressBar) return;
            const scrollTop = window.scrollY;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const scrollPercent = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
            progressBar.style.width = scrollPercent + '%';
        });

        // Attach safe listener to start reading button: close modal (handled by data-bs-dismiss) then scroll to content
        document.addEventListener('DOMContentLoaded', function() {
            const startBtn = document.getElementById('startReadingBtn');
            if (startBtn) {
                startBtn.addEventListener('click', function() {
                    // Give bootstrap a moment to dismiss the modal, then scroll
                    setTimeout(function() {
                        const el = document.querySelector('.full-content') || document.querySelector('.reader-content');
                        if (el) {
                            el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                            // Accessibility: focus content container
                            try { el.setAttribute('tabindex', '-1'); el.focus(); } catch (e) { /* ignore */ }
                        }
                    }, 220);
                });
            }

            // Set minimum due date safely (only if element exists)
            const dueDateEl = document.getElementById('dueDate');
            if (dueDateEl) {
                dueDateEl.addEventListener('change', function() {
                    const borrowDateEl = document.getElementById('borrowDate');
                    if (!borrowDateEl) return;
                    const borrowDate = new Date(borrowDateEl.value);
                    const dueDate = new Date(this.value);
                    const diffTime = Math.abs(dueDate - borrowDate);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    if (diffDays > 14) {
                        alert('Durasi peminjaman maksimal 7 hari!');
                        this.value = '';
                    }
                });
            }
        });
    </script>
</body>
</html>
