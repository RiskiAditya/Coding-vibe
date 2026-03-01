@extends('admin.layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('admin.returns.index') }}" class="text-blue-500 hover:text-blue-700 mb-4 inline-block">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Detail Pengembalian Buku</h1>
        </div>

        <!-- Main Info Card -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Informasi Pengembalian</h2>
            </div>
            <div class="px-6 py-4">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-gray-600 text-sm font-semibold mb-1">Anggota</p>
                        <p class="text-lg font-bold text-gray-800">{{ $return->borrowing->user->name }}</p>
                        <p class="text-gray-600 text-sm">{{ $return->borrowing->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-semibold mb-1">Tanggal Pengembalian</p>
                        <p class="text-lg font-bold text-gray-800">{{ $return->return_date->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Book Info Card -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Informasi Buku</h2>
            </div>
            <div class="px-6 py-4">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-gray-600 text-sm font-semibold mb-1">Judul</p>
                        <p class="text-lg font-bold text-gray-800">{{ $return->borrowing->book->title }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-semibold mb-1">ISBN</p>
                        <p class="text-lg font-bold text-gray-800">{{ $return->borrowing->book->isbn }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-semibold mb-1">Pengarang</p>
                        <p class="text-gray-800">{{ $return->borrowing->book->author }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-semibold mb-1">Penerbit</p>
                        <p class="text-gray-800">{{ $return->borrowing->book->publisher }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Condition & Notes Card -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Kondisi & Catatan</h2>
            </div>
            <div class="px-6 py-4">
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-gray-600 text-sm font-semibold mb-2">Kondisi Buku</p>
                        @if($return->book_condition === 'good')
                            <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                                <i class="fas fa-check-circle me-1"></i>Baik
                            </span>
                        @elseif($return->book_condition === 'damaged')
                            <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold">
                                <i class="fas fa-exclamation-triangle me-1"></i>Rusak
                            </span>
                        @else
                            <span class="inline-block px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-semibold">
                                <i class="fas fa-times-circle me-1"></i>Hilang
                            </span>
                        @endif
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-semibold mb-1">Petugas</p>
                        <p class="text-gray-800">{{ $return->staff->name ?? '-' }}</p>
                    </div>
                </div>
                @if($return->notes)
                    <div class="bg-blue-50 border border-blue-200 rounded p-4">
                        <p class="text-gray-600 text-sm font-semibold mb-1">Catatan:</p>
                        <p class="text-gray-800">{{ $return->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Borrowing Timeline -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Timeline Peminjaman</h2>
            </div>
            <div class="px-6 py-4">
                <div class="space-y-4">
                    <div class="flex">
                        <div class="flex-shrink-0 mr-4">
                            <div class="flex items-center justify-center h-10 w-10 rounded-full bg-blue-500 text-white">
                                <i class="fas fa-arrow-down"></i>
                            </div>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Tanggal Peminjaman</p>
                            <p class="font-bold text-gray-800">{{ $return->borrowing->borrow_date->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <div class="flex">
                        <div class="flex-shrink-0 mr-4">
                            <div class="flex items-center justify-center h-10 w-10 rounded-full bg-yellow-500 text-white">
                                <i class="fas fa-calendar"></i>
                            </div>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Jatuh Tempo</p>
                            <p class="font-bold text-gray-800">{{ $return->borrowing->due_date->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <div class="flex">
                        <div class="flex-shrink-0 mr-4">
                            <div class="flex items-center justify-center h-10 w-10 rounded-full bg-green-500 text-white">
                                <i class="fas fa-arrow-up"></i>
                            </div>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Tanggal Pengembalian</p>
                            <p class="font-bold text-gray-800">{{ $return->return_date->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-2">
            <a href="{{ route('admin.returns.index') }}" class="flex-1 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-center">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
            <form action="{{ route('admin.returns.destroy', $return) }}" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Hapus record pengembalian ini?')">
                    <i class="fas fa-trash me-2"></i>Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
