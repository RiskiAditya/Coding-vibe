{{-- Admin Book Card Component --}}
{{-- Usage: @include('components.admin-book-card', ['book' => $book]) --}}
@php
    $isArray = is_array($book);
    $image = $isArray ? ($book['cover'] ?? $book['image'] ?? null) : ($book->image ?? null);
    $title = $isArray ? ($book['title'] ?? '') : ($book->title ?? '');
    $author = $isArray ? ($book['author'] ?? '') : ($book->author ?? '');
    $description = $isArray ? ($book['description'] ?? 'Tidak ada deskripsi') : ($book->description ?: 'Tidak ada deskripsi');
    $availableQuantity = $isArray ? 1 : ($book->available_quantity ?? 0);
    $quantity = $isArray ? 1 : ($book->quantity ?? 0);

    // Set category and default image based on title keywords
    $category = 'Umum';
    $defaultImage = 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'; // Default book image
    $titleLower = strtolower($title);
    if (strpos($titleLower, 'sejarah') !== false) {
        $category = 'Sejarah';
        $defaultImage = 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'; // Ancient books/history
    } elseif (strpos($titleLower, 'teknologi') !== false || strpos($titleLower, 'ai') !== false) {
        $category = 'Teknologi';
        $defaultImage = 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'; // Technology/circuit board
    } elseif (strpos($titleLower, 'sastra') !== false || strpos($titleLower, 'literatur') !== false) {
        $category = 'Sastra';
        $defaultImage = 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'; // Classic literature
    } elseif (strpos($titleLower, 'matematika') !== false || strpos($titleLower, 'fisika') !== false) {
        $category = 'Sains';
        $defaultImage = 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'; // Math equations
    } elseif (strpos($titleLower, 'biologi') !== false || strpos($titleLower, 'kimia') !== false) {
        $category = 'Biologi';
        $defaultImage = 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'; // Laboratory/microscope
    }
@endphp
<div class="bg-white rounded-[2rem] border-4 border-amber-800 shadow-2xl p-6 max-w-sm mx-auto relative book-card min-h-[500px] flex flex-col" data-book-id="{{ $book->id }}">
    {{-- Admin Actions Overlay --}}
    <div class="absolute top-4 right-4 flex space-x-2 z-10">
        <button type="button" class="edit-toggle bg-yellow-500 hover:bg-yellow-700 text-white p-2 rounded-full shadow-lg" title="Edit Inline">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
        </button>
        <a href="{{ route('admin.books.show', $book) }}" class="bg-blue-500 hover:bg-blue-700 text-white p-2 rounded-full shadow-lg" title="Lihat Detail">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
        </a>
        <form action="{{ route('admin.books.destroy', $book) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white p-2 rounded-full shadow-lg" title="Hapus">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </form>
    </div>

    {{-- Book Cover Image --}}
    @if($image)
        @if($isArray)
            <img src="{{ $image }}" alt="{{ $title }}" class="h-64 w-full rounded-xl object-cover mb-4">
        @else
            <img src="{{ asset('images/books/' . $image) }}" alt="{{ $title }}" class="h-64 w-full rounded-xl object-cover mb-4">
        @endif
    @else
        <img src="{{ $defaultImage }}" alt="{{ $title }}" class="h-64 w-full rounded-xl object-cover mb-4">
    @endif

    {{-- Book Title --}}
    <h3 class="text-2xl font-bold text-center mb-4 text-gray-800">{{ $title }}</h3>

    {{-- Book Metadata --}}
    <div class="space-y-2 text-left">
        {{-- Title --}}
        <p class="text-lg text-gray-700 mb-4">{{ $title }}</p>

        {{-- Author --}}
        <p class="text-gray-700"><span class="font-semibold">Pengarang/Pembuat:</span> {{ $author }}</p>

        {{-- Category --}}
        <p class="text-gray-700"><span class="font-semibold">Kategori:</span> {{ $category }}</p>

        {{-- Description --}}
        <p class="text-gray-700"><span class="font-semibold">Deskripsi:</span> {{ $description }}</p>

        {{-- Quantity --}}
        @if(!$isArray)
        <p class="text-gray-700"><span class="font-semibold">Tersedia:</span>
            <span class="px-2 py-1 rounded text-xs {{ $availableQuantity > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ $availableQuantity }}/{{ $quantity }}
            </span>
        </p>
        @endif
    </div>


</div>
