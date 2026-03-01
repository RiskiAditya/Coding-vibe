@extends('admin.layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Edit Peminjaman</h2>
        </div>
        <form action="{{ route('admin.borrowings.update', $borrowing) }}" method="POST" class="px-6 py-4">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="return_date" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Kembali:</label>
                <input type="date" name="return_date" id="return_date" value="{{ old('return_date', $borrowing->return_date ? $borrowing->return_date->format('Y-m-d') : '') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('return_date') border-red-500 @enderror">
                @error('return_date')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status:</label>
                <select name="status" id="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('status') border-red-500 @enderror" required>
                    <option value="active" {{ old('status', $borrowing->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="returned" {{ old('status', $borrowing->status) == 'returned' ? 'selected' : '' }}>Returned</option>
                    <option value="overdue" {{ old('status', $borrowing->status) == 'overdue' ? 'selected' : '' }}>Overdue</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="condition" class="block text-gray-700 text-sm font-bold mb-2">Kondisi Buku:</label>
                <select name="condition" id="condition" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('condition') border-red-500 @enderror">
                    <option value="good" {{ old('condition') == 'good' ? 'selected' : '' }}>Baik</option>
                    <option value="late" {{ old('condition') == 'late' ? 'selected' : '' }}>Terlambat</option>
                    <option value="damaged" {{ old('condition') == 'damaged' ? 'selected' : '' }}>Rusak</option>
                    <option value="lost" {{ old('condition') == 'lost' ? 'selected' : '' }}>Hilang</option>
                </select>
                @error('condition')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Update Peminjaman
                </button>
                <a href="{{ route('admin.borrowings.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
