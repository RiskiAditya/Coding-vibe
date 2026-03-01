@extends('admin.layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Catat Pengembalian Buku</h2>
        </div>
        <form action="{{ route('admin.returns.store') }}" method="POST" class="px-6 py-4">
            @csrf
            <div class="mb-4">
                <label for="borrowing_id" class="block text-gray-700 text-sm font-bold mb-2">Peminjaman:</label>
                <select name="borrowing_id" id="borrowing_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('borrowing_id') border-red-500 @enderror" required onchange="updateBorrowingInfo()">
                    <option value="">Pilih Peminjaman</option>
                    @foreach($borrowings as $borrowing)
                        <option value="{{ $borrowing->id }}" data-member="{{ $borrowing->user->name }}" data-book="{{ $borrowing->book->title }}" data-date="{{ $borrowing->borrow_date->format('d/m/Y') }}" data-due="{{ $borrowing->due_date->format('d/m/Y') }}">
                            {{ $borrowing->user->name }} - {{ $borrowing->book->title }} (Pinjam: {{ $borrowing->borrow_date->format('d/m/Y') }})
                        </option>
                    @endforeach
                </select>
                @error('borrowing_id')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <!-- Info Box -->
            <div id="infoBox" class="hidden bg-blue-50 border border-blue-200 rounded p-4 mb-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-600">Anggota</p>
                        <p id="memberName" class="font-semibold text-gray-800">-</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600">Buku</p>
                        <p id="bookTitle" class="font-semibold text-gray-800">-</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600">Tanggal Pinjam</p>
                        <p id="borrowDate" class="font-semibold text-gray-800">-</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600">Jatuh Tempo</p>
                        <p id="dueDate" class="font-semibold text-gray-800">-</p>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label for="book_condition" class="block text-gray-700 text-sm font-bold mb-2">Kondisi Buku:</label>
                <select name="book_condition" id="book_condition" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('book_condition') border-red-500 @enderror" required>
                    <option value="">Pilih Kondisi</option>
                    <option value="good" {{ old('book_condition') == 'good' ? 'selected' : '' }}>Baik</option>
                    <option value="damaged" {{ old('book_condition') == 'damaged' ? 'selected' : '' }}>Rusak</option>
                    <option value="lost" {{ old('book_condition') == 'lost' ? 'selected' : '' }}>Hilang</option>
                </select>
                @error('book_condition')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="notes" class="block text-gray-700 text-sm font-bold mb-2">Catatan:</label>
                <textarea name="notes" id="notes" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    <i class="fas fa-check me-2"></i>Simpan Pengembalian
                </button>
                <a href="{{ route('admin.returns.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function updateBorrowingInfo() {
    const select = document.getElementById('borrowing_id');
    const option = select.options[select.selectedIndex];
    const infoBox = document.getElementById('infoBox');
    
    if(option.value) {
        document.getElementById('memberName').textContent = option.dataset.member;
        document.getElementById('bookTitle').textContent = option.dataset.book;
        document.getElementById('borrowDate').textContent = option.dataset.date;
        document.getElementById('dueDate').textContent = option.dataset.due;
        infoBox.classList.remove('hidden');
    } else {
        infoBox.classList.add('hidden');
    }
}
</script>
@endsection
