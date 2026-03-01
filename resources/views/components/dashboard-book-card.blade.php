{{-- Dashboard Book Card Component with Admin Actions --}}
{{-- Usage: @include('components.dashboard-book-card', ['book' => $book]) --}}
<div class="col-lg-3 col-md-4 col-sm-6">
    <div class="card shadow-sm h-100 position-relative">
        {{-- Admin Actions Overlay --}}
        <div class="position-absolute top-0 end-0 p-2 d-flex gap-1 z-index-10">
            <a href="{{ route('admin.books.show', $book) }}" class="btn btn-sm btn-info rounded-circle" title="Lihat Detail" style="width: 30px; height: 30px; padding: 0;">
                <i class="fas fa-eye fa-xs"></i>
            </a>
            <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-sm btn-warning rounded-circle" title="Edit" style="width: 30px; height: 30px; padding: 0;">
                <i class="fas fa-edit fa-xs"></i>
            </a>
            <form action="{{ route('admin.books.destroy', $book) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger rounded-circle" title="Hapus" style="width: 30px; height: 30px; padding: 0;">
                    <i class="fas fa-trash fa-xs"></i>
                </button>
            </form>
        </div>

        {{-- Featured Badge --}}
        @if($book->featured)
            <div class="position-absolute top-0 start-0 m-2 z-index-5">
                <span class="badge bg-warning text-dark">
                    <i class="fas fa-star me-1"></i>Unggulan
                </span>
            </div>
        @endif

        {{-- Book Image --}}
        @if($book->image)
            <img src="{{ asset('images/books/' . $book->image) }}" class="card-img-top" alt="{{ $book->title }}" style="height: 200px; object-fit: cover;">
        @else
            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                <i class="fas fa-book fa-3x text-muted"></i>
            </div>
        @endif

        <div class="card-body d-flex flex-column">
            <h5 class="card-title">{{ $book->title }}</h5>
            <p class="card-text text-muted mb-2">
                <i class="fas fa-user me-1"></i>{{ $book->author }}
            </p>
            <p class="card-text small text-truncate">{{ $book->description }}</p>
            <div class="mt-auto">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted">
                        <i class="fas fa-tags me-1"></i>{{ $book->category ? $book->category->name : 'Uncategorized' }}
                    </small>
                    <span class="badge {{ $book->available_quantity > 0 ? 'bg-success' : 'bg-danger' }}">
                        {{ $book->available_quantity }} tersedia
                    </span>
                </div>

                {{-- Toggle Featured Button --}}
                <form action="{{ route('admin.books.toggle-featured', $book) }}" method="POST" class="mb-2">
                    @csrf
                    @method('POST')
                    <button type="submit" class="btn btn-sm w-100 {{ $book->featured ? 'btn-warning' : 'btn-outline-secondary' }}">
                        <i class="fas {{ $book->featured ? 'fa-star' : 'fa-star-o' }} me-1"></i>
                        {{ $book->featured ? 'Hapus Unggulan' : 'Jadikan Unggulan' }}
                    </button>
                </form>

                <a href="{{ route('buku') }}" class="btn btn-primary btn-sm w-100">
                    <i class="fas fa-eye me-1"></i>Lihat Detail
                </a>
            </div>
        </div>
    </div>
</div>
