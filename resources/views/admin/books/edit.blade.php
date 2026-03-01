@extends('admin.layout')

@section('title', 'Edit Buku')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header" style="background-color: #92400e;">
                    <h4 class="mb-0 text-white">
                        <i class="fas fa-edit me-2"></i>Edit Buku: {{ $book->title }}
                    </h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>
                                <i class="fas fa-exclamation-circle me-2"></i>Terjadi Kesalahan!
                            </strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.books.update', $book) }}" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="title" class="form-label fw-bold">
                                    <i class="fas fa-heading me-1"></i>Judul Buku <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" 
                                    placeholder="Masukkan judul buku" value="{{ old('title', $book->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="author" class="form-label fw-bold">
                                    <i class="fas fa-user-pen me-1"></i>Pengarang <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="author" name="author" class="form-control @error('author') is-invalid @enderror"
                                    placeholder="Masukkan nama pengarang" value="{{ old('author', $book->author) }}" required>
                                @error('author')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="isbn" class="form-label fw-bold">
                                    <i class="fas fa-barcode me-1"></i>ISBN <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="isbn" name="isbn" class="form-control @error('isbn') is-invalid @enderror"
                                    placeholder="Masukkan ISBN" value="{{ old('isbn', $book->isbn) }}" required>
                                @error('isbn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="publisher" class="form-label fw-bold">
                                    <i class="fas fa-print me-1"></i>Penerbit
                                </label>
                                <input type="text" id="publisher" name="publisher" class="form-control @error('publisher') is-invalid @enderror"
                                    placeholder="Masukkan nama penerbit" value="{{ old('publisher', $book->publisher) }}">
                                @error('publisher')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="category_id" class="form-label fw-bold">
                                    <i class="fas fa-folder me-1"></i>Kategori <span class="text-danger">*</span>
                                </label>
                                <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach(\App\Models\Category::all() as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="publication_year" class="form-label fw-bold">
                                    <i class="fas fa-calendar me-1"></i>Tahun Terbit
                                </label>
                                <input type="number" id="publication_year" name="publication_year" class="form-control @error('publication_year') is-invalid @enderror"
                                    placeholder="Contoh: 2024" value="{{ old('publication_year', $book->publication_year) }}" min="1000" max="{{ date('Y') + 1 }}">
                                @error('publication_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="quantity" class="form-label fw-bold">
                                    <i class="fas fa-list-ol me-1"></i>Jumlah Stok <span class="text-danger">*</span>
                                </label>
                                <input type="number" id="quantity" name="quantity" class="form-control @error('quantity') is-invalid @enderror"
                                    placeholder="Masukkan jumlah buku" value="{{ old('quantity', $book->quantity) }}" min="1" required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="image" class="form-label fw-bold">
                                    <i class="fas fa-image me-1"></i>Gambar Sampul
                                </label>
                                <input type="file" id="image" name="image" class="form-control @error('image') is-invalid @enderror"
                                    accept="image/jpeg,image/png,image/gif,image/jpg">
                                <small class="text-muted">Format: JPG, PNG, GIF (Max: 2MB)</small>
                                @if($book->image)
                                    <div class="mt-2">
                                        <small class="text-muted d-block mb-1">Gambar saat ini:</small>
                                        <img src="{{ asset('images/books/' . $book->image) }}" alt="{{ $book->title }}" class="img-thumbnail" style="max-width: 150px;">
                                    </div>
                                @endif
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">
                                <i class="fas fa-align-left me-1"></i>Deskripsi
                            </label>
                            <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror"
                                rows="4" placeholder="Masukkan deskripsi buku">{{ old('description', $book->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="enable_category_filter" name="enable_category_filter" 
                                    value="1" {{ old('enable_category_filter', $book->enable_category_filter) ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_category_filter">
                                    <i class="fas fa-filter me-1"></i><strong>Tampilkan Filter Kategori di Halaman Baca</strong>
                                </label>
                                <small class="d-block text-muted mt-1">
                                    Jika diaktifkan, pembaca dapat memfilter buku berdasarkan kategori saat membaca buku ini.
                                </small>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.books.index') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
