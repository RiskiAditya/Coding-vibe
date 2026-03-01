@extends('admin.layout')

@section('title', 'Kelola Buku')

@section('content')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Buku - {{ config('app.name', 'Aplikasi Perpustakaan') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    {{-- Back to Dashboard Button --}}
    <div class="mb-4 px-4 pt-4">
        <a href="{{ route('dashboard') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-300 shadow-md hover:shadow-lg">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard Admin
        </a>
    </div>

    {{-- Featured Books Section --}}
    <div id="buku" class="py-16 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Kelola Buku</h2>
                <p class="text-lg text-gray-600">Kelola koleksi buku perpustakaan Anda</p>
                <a href="{{ route('admin.books.create') }}" class="inline-block mt-4 bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-8 rounded-full transition duration-300 shadow-lg hover:shadow-xl">
                    <i class="fas fa-plus me-2"></i>Tambah Buku Baru
                </a>
            </div>

            {{-- Search and Filter Forms --}}
            <div class="mb-8">
                <div class="flex flex-col md:flex-row gap-4 justify-center">
                    {{-- Search Form --}}
                    <form method="GET" action="{{ route('admin.books.index') }}" class="flex">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari buku..." class="px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                        <button type="submit" class="px-6 py-2 bg-amber-600 text-white rounded-r-md hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                            Cari
                        </button>
                    </form>

                    {{-- Filter Form --}}
                    <form method="GET" action="{{ route('admin.books.index') }}" class="flex gap-2">
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
            @if($books->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($books as $book)
                        @include('components.admin-book-card', ['book' => $book])
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada buku</h5>
                    <p class="text-muted">Koleksi buku akan segera ditambahkan.</p>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
@endsection
