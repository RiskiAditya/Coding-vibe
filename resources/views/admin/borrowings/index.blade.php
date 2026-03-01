@extends('admin.layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Peminjaman</h1>
            <p class="text-sm text-gray-500">Kelola semua peminjaman anggota di sini.</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.location.href='{{ route('dashboard') }}'" class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg shadow border-0 cursor-pointer">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </button>
            <a href="{{ route('admin.borrowings.create') }}" class="inline-flex items-center gap-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2 px-4 rounded-lg shadow">
                <i class="fas fa-plus"></i> Tambah Peminjaman
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-4 border-b">
            <form method="GET" action="{{ route('admin.borrowings.index') }}" class="flex flex-col md:flex-row gap-3 md:items-center">
                <div class="flex items-center gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama anggota atau judul buku..." class="px-3 py-2 border rounded-md w-64" />
                    <select name="status" class="px-3 py-2 border rounded-md">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status')=='active' ? 'selected' : '' }}>Active</option>
                        <option value="returned" {{ request('status')=='returned' ? 'selected' : '' }}>Returned</option>
                        <option value="overdue" {{ request('status')=='overdue' ? 'selected' : '' }}>Overdue</option>
                        <option value="return_requested" {{ request('status')=='return_requested' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                    </select>
                </div>
                <div class="ml-auto flex items-center gap-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md">Filter</button>
                    <a href="{{ route('admin.borrowings.index') }}" class="text-sm text-gray-600 hover:underline">Reset</a>
                </div>
            </form>
        </div>
        <div class="overflow-x-auto">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Anggota</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buku</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pinjam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pengembalian</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Denda</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($borrowings as $borrowing)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $borrowing->user->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $borrowing->book->title }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $borrowing->borrow_date->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $borrowing->due_date->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($borrowing->status == 'return_requested')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu Konfirmasi</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($borrowing->status == 'active') bg-blue-100 text-blue-800
                                    @elseif($borrowing->status == 'returned') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $borrowing->status }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            Rp {{ number_format($borrowing->getTotalFines()) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex flex-wrap items-center gap-2">
                                <a href="{{ route('admin.borrowings.show', $borrowing) }}" class="inline-flex items-center gap-2 text-white bg-indigo-600 hover:bg-indigo-700 px-3 py-1 rounded-md mb-1">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                                <a href="{{ route('admin.borrowings.edit', $borrowing) }}" class="inline-flex items-center gap-2 text-white bg-yellow-500 hover:bg-yellow-600 px-3 py-1 rounded-md mb-1">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                @if($borrowing->status === 'active' || $borrowing->status === 'return_requested')
                                    <button type="button" class="inline-flex items-center gap-2 text-white bg-green-600 hover:bg-green-700 px-3 py-1 rounded-md mb-1" data-bs-toggle="modal" data-bs-target="#confirmReturnModal" data-borrowing-id="{{ $borrowing->id }}" data-book-title="{{ $borrowing->book->title }}" data-member-name="{{ $borrowing->user->name }}">
                                        <i class="fas fa-check-circle"></i> Konfirmasi Pengembalian
                                    </button>
                                @endif
                                <form action="{{ route('admin.borrowings.destroy', $borrowing) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus peminjaman ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-2 text-white bg-red-600 hover:bg-red-700 px-3 py-1 rounded-md mb-1">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada data peminjaman.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
        <div class="p-4 border-t flex items-center justify-between">
            <div class="text-sm text-gray-600">Menampilkan {{ $borrowings->count() }} dari {{ $borrowings->total() }} peminjaman</div>
            <div>
                {{ $borrowings->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Pengembalian -->
<div class="modal fade" id="confirmReturnModal" tabindex="-1" aria-labelledby="confirmReturnLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="confirmReturnLabel">
                    <i class="fas fa-check-circle me-2"></i>Konfirmasi Pengembalian Buku
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info mb-4" role="alert">
                    <strong id="memberName"></strong> mengembalikan buku <strong id="bookTitle"></strong>
                </div>

                <form id="returnForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="condition" class="form-label fw-bold">
                            <i class="fas fa-info-circle me-2"></i>Kondisi Buku
                        </label>
                        <select id="condition" name="condition" class="form-select" required>
                            <option value="" disabled selected>-- Pilih Kondisi Buku --</option>
                            <option value="good">
                                ✓ Baik - Tidak ada kerusakan
                            </option>
                            <option value="damaged">
                                ⚠ Rusak - Ada kerusakan minor
                            </option>
                            <option value="lost">
                                ✗ Hilang - Buku tidak dikembalikan
                            </option>
                        </select>
                    </div>

                    <div class="alert alert-warning d-none" id="damageAlert" role="alert">
                        <small>
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            Akan dikenakan denda Rp 25.000 untuk kerusakan.
                        </small>
                    </div>

                    <div class="alert alert-danger d-none" id="lostAlert" role="alert">
                        <small>
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            Akan dikenakan denda penggantian buku sesuai harga buku.
                        </small>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan (Opsional)</label>
                        <textarea id="notes" name="notes" class="form-control" rows="3" placeholder="Catatan kondisi buku atau observasi lainnya..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Batal
                </button>
                <button type="button" class="btn btn-success" id="confirmBtn" onclick="submitReturnForm()">
                    <i class="fas fa-check me-1"></i>Konfirmasi Pengembalian
                </button>
            </div>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let currentBorrowingId = null;

    // Ketika modal dibuka, populate data
    document.getElementById('confirmReturnModal').addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        currentBorrowingId = button.getAttribute('data-borrowing-id');
        const bookTitle = button.getAttribute('data-book-title');
        const memberName = button.getAttribute('data-member-name');

        document.getElementById('bookTitle').textContent = bookTitle;
        document.getElementById('memberName').textContent = memberName;
        document.getElementById('condition').value = '';
        document.getElementById('notes').value = '';
        document.getElementById('damageAlert').classList.add('d-none');
        document.getElementById('lostAlert').classList.add('d-none');

        // Set form action ke route yang benar
        const form = document.getElementById('returnForm');
        form.action = `/admin/borrowings/${currentBorrowingId}/return`;
    });

    // Monitor perubahan kondisi buku untuk tampilkan alert
    document.getElementById('condition').addEventListener('change', function() {
        const damageAlert = document.getElementById('damageAlert');
        const lostAlert = document.getElementById('lostAlert');

        damageAlert.classList.add('d-none');
        lostAlert.classList.add('d-none');

        if (this.value === 'damaged') {
            damageAlert.classList.remove('d-none');
        } else if (this.value === 'lost') {
            lostAlert.classList.remove('d-none');
        }
    });

    // Submit form dengan metode POST
    function submitReturnForm() {
        const condition = document.getElementById('condition').value;
        if (!condition) {
            alert('Pilih kondisi buku terlebih dahulu');
            return;
        }

        const form = document.getElementById('returnForm');
        form.submit();
    }
</script>
@endsection
