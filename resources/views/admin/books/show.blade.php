@extends('admin.layout')

@section('title', 'Detail Buku')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12 mb-3">
            <a href="{{ url('/semua-buku') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Semua Buku
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-lg h-100">
                <div class="card-body text-center">
                    @if($book->image)
                        <img src="{{ asset('images/books/' . $book->image) }}" alt="{{ $book->title }}" class="img-fluid rounded mb-3" style="max-height: 400px; object-fit: cover;">
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 400px;">
                            <i class="fas fa-book fa-5x text-muted"></i>
                        </div>
                    @endif
                    
                    <div class="mt-3 d-flex gap-2">
                        <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-warning btn-sm flex-grow-1">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <form action="{{ route('admin.books.destroy', $book) }}" method="POST" class="flex-grow-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm w-100">
                                <i class="fas fa-trash me-1"></i>Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header" style="background-color: #92400e;">
                    <h4 class="mb-0 text-white">
                        <i class="fas fa-book me-2"></i>{{ $book->title }}
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Pengarang</h6>
                            <p class="fs-5 fw-bold">{{ $book->author }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">ISBN</h6>
                            <p class="fs-5 fw-bold">{{ $book->isbn }}</p>
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
                            <h6 class="text-muted">Status Unggulan</h6>
                            <p class="fs-5">
                                @if($book->featured)
                                    <span class="badge bg-success">
                                        <i class="fas fa-star me-1"></i>Unggulan
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Biasa</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Jumlah Stok</h6>
                            <p class="fs-5 fw-bold">{{ $book->quantity }} Buku</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Tersedia</h6>
                            <p class="fs-5">
                                @if($book->available_quantity > 0)
                                    <span class="badge bg-success">{{ $book->available_quantity }} Buku</span>
                                @else
                                    <span class="badge bg-danger">Habis</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted">Deskripsi</h6>
                        <p class="fs-5" style="line-height: 1.6;">
                            {{ $book->description ?? 'Tidak ada deskripsi.' }}
                        </p>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-muted mb-3">Aksi</h6>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-warning">
                                    <i class="fas fa-edit me-2"></i>Edit Buku
                                </a>
                                <form action="{{ route('admin.books.destroy', $book) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash me-2"></i>Hapus Buku
                                    </button>
                                </form>
                                <form action="{{ route('admin.books.toggle-featured', $book) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn {{ $book->featured ? 'btn-warning' : 'btn-outline-warning' }}">
                                        <i class="fas fa-star me-2"></i>{{ $book->featured ? 'Hapus dari Unggulan' : 'Jadikan Unggulan' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
