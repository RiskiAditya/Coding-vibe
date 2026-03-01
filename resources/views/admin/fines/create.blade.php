@extends('admin.layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Tambah Denda Baru</h2>
        </div>
        <form action="{{ route('admin.fines.store') }}" method="POST" class="px-6 py-4">
            @csrf
            <div class="mb-4">
                <label for="borrowing_id" class="block text-gray-700 text-sm font-bold mb-2">Peminjaman:</label>
                <select name="borrowing_id" id="borrowing_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('borrowing_id') border-red-500 @enderror" required>
                    <option value="">Pilih Peminjaman</option>
                    @foreach(\App\Models\Borrowing::with(['user', 'book'])->get() as $borrowing)
                        <option value="{{ $borrowing->id }}" {{ old('borrowing_id') == $borrowing->id ? 'selected' : '' }}>
                            {{ $borrowing->user->name }} - {{ $borrowing->book->title }}
                        </option>
                    @endforeach
                </select>
                @error('borrowing_id')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="amount" class="block text-gray-700 text-sm font-bold mb-2">Jumlah (Rp):</label>
                <input type="number" name="amount" id="amount" value="{{ old('amount') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('amount') border-red-500 @enderror" required min="0">
                @error('amount')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="reason" class="block text-gray-700 text-sm font-bold mb-2">Alasan:</label>
                <textarea name="reason" id="reason" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('reason') border-red-500 @enderror" required>{{ old('reason') }}</textarea>
                @error('reason')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status:</label>
                <select name="status" id="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('status') border-red-500 @enderror" required>
                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Belum Lunas</option>
                    <option value="waiting_confirmation" {{ old('status') == 'waiting_confirmation' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                    <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Tambah Denda
                </button>
                <a href="{{ route('admin.fines.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
