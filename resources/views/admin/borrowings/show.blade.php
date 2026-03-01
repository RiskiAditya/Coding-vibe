@extends('admin.layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Detail Peminjaman</h2>
        </div>
        <div class="px-6 py-4">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Anggota:</label>
                <p class="text-gray-900">{{ $borrowing->user->name }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Buku:</label>
                <p class="text-gray-900">{{ $borrowing->book->title }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Pinjam:</label>
                <p class="text-gray-900">{{ $borrowing->borrow_date->format('d M Y') }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Pengembalian:</label>
                <p class="text-gray-900">{{ $borrowing->due_date->format('d M Y') }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Kembali:</label>
                <p class="text-gray-900">{{ $borrowing->return_date ? $borrowing->return_date->format('d M Y') : '-' }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Status:</label>
                <p class="text-gray-900">{{ $borrowing->status }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Total Denda:</label>
                <p class="text-gray-900">Rp {{ number_format($borrowing->getTotalFines()) }}</p>
            </div>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <a href="{{ route('admin.borrowings.edit', $borrowing) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-2">
                Edit
            </a>
            <a href="{{ url('/semua-buku') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Kembali ke Semua Buku
            </a>
        </div>
    </div>
</div>
@endsection
