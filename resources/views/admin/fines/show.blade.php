@extends('admin.layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Detail Denda</h2>
        </div>
        <div class="px-6 py-4">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Anggota:</label>
                <p class="text-gray-900">{{ $fine->borrowing->user->name }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Buku:</label>
                <p class="text-gray-900">{{ $fine->borrowing->book->title }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah:</label>
                <p class="text-gray-900">Rp {{ number_format($fine->amount) }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Alasan:</label>
                <p class="text-gray-900">{{ $fine->reason }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Status:</label>
                <p class="text-gray-900">{{ $fine->status == 'paid' ? 'Lunas' : 'Belum Lunas' }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Pengembalian:</label>
                <p class="text-gray-900">{{ $fine->due_date ? $fine->due_date->format('d M Y') : '-' }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Dibayar:</label>
                <p class="text-gray-900">{{ $fine->paid_date ? $fine->paid_date->format('d M Y') : '-' }}</p>
            </div>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            @if($fine->status != 'paid')
                <form action="{{ route('admin.fines.markAsPaid', $fine) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-2">
                        Tandai Lunas
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.fines.edit', $fine) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-2">
                Edit
            </a>
            <a href="{{ route('admin.fines.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Kembali
            </a>
        </div>
    </div>
</div>
@endsection
