{{-- Book Card Component --}}
{{-- Usage: @include('components.book-card', ['book' => $book]) --}}
@php
    $isArray = is_array($book);
    $image = $isArray ? ($book['cover'] ?? $book['image'] ?? null) : ($book->image ?? null);
    $title = $isArray ? ($book['title'] ?? '') : ($book->title ?? '');
    $author = $isArray ? ($book['author'] ?? '') : ($book->author ?? '');
    $description = $isArray ? ($book['description'] ?? 'Tidak ada deskripsi') : ($book->description ?: 'Tidak ada deskripsi');
    $availableQuantity = $isArray ? 1 : ($book->available_quantity ?? 0);
    $quantity = $isArray ? 1 : ($book->quantity ?? 0);

    // Get category from database, fallback to Umum if not set
    $category = $isArray ? ($book['category'] ?? 'Umum') : ($book->category?->name ?? 'Umum');
    
    // Set default image based on category
    $defaultImages = [
        'Sejarah' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
        'Teknologi' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
        'Sastra' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
        'Sains' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
        'Biologi' => 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
    ];
    $defaultImage = $defaultImages[$category] ?? 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80';
@endphp
<div class="bg-white rounded-[2rem] border-4 border-amber-800 shadow-2xl p-6 max-w-sm mx-auto">
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

    {{-- CTA Buttons --}}
    <div class="mt-6 space-y-3">
        {{-- If sample view is active, always show the sample message instead of action buttons --}}
        @if($isSampleView ?? false)
            <div class="text-center text-gray-700 text-sm bg-yellow-50 p-3 rounded-lg border border-yellow-200">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Buku sampel - Daftar untuk akses penuh</strong>
            </div>
        @else
            @if(Auth::check())
                @if(!$isArray)
                    <a href="{{ route('books.read', $book->id) }}" class="block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-full transition duration-300 shadow-lg hover:shadow-xl text-center">
                        <i class="fas fa-book-open me-2"></i>Baca Digital
                    </a>
                    <a href="{{ route('book-detail', $book->id) }}" class="block bg-amber-800 hover:bg-amber-900 text-white font-bold py-2 px-6 rounded-full transition duration-300 shadow-lg hover:shadow-xl text-center">
                        <i class="fas fa-bookmark me-2"></i>Pinjam Buku
                    </a>
                @else
                    <div class="text-center text-gray-500 text-sm">
                        <i class="fas fa-info-circle me-2"></i>Buku sampel - Daftar untuk akses penuh
                    </div>
                @endif
            @else
                @if(!$isArray)
                    <div class="text-center bg-blue-50 rounded-lg p-3 border border-blue-200">
                        <p class="text-blue-700 text-sm font-semibold mb-2">
                            <i class="fas fa-lock me-2"></i>Buku sampel
                        </p>
                        <p class="text-blue-600 text-xs mb-3">Daftar untuk akses penuh</p>
                        <a href="{{ route('login') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full transition duration-300 text-sm">
                            <i class="fas fa-sign-in-alt me-1"></i>Login / Daftar
                        </a>
                    </div>
                @else
                    <button type="button" onclick="openAuthModal()" class="w-full bg-amber-800 hover:bg-amber-900 text-white font-bold py-2 px-6 rounded-full transition duration-300 shadow-lg hover:shadow-xl">
                        <i class="fas fa-book-open me-2"></i>Baca?
                    </button>
                @endif
            @endif
        @endif
    </div>
</div>
