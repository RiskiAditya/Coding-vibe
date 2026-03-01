@extends('admin.layout')

@section('content')
<div class="container-fluid px-4 py-5">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-money-bill-wave me-2" style="color: #dc3545;"></i>Kelola Denda
            </h1>
            <small class="text-muted">Pantau dan kelola pembayaran denda anggota perpustakaan</small>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.location.href='{{ route('dashboard') }}'" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </button>
            <a href="{{ route('admin.fines.create') }}" class="btn btn-danger">
                <i class="fas fa-plus me-2"></i>Tambah Denda
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card border-left-danger shadow h-100">
                <div class="card-body">
                    <div class="text-danger fw-bold text-uppercase mb-1">
                        <i class="fas fa-hourglass-end me-2"></i>Denda Pending
                    </div>
                    <div class="h3 mb-0" style="color: #dc3545;">
                        Rp {{ number_format($totalPending ?? 0, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-left-success shadow h-100">
                <div class="card-body">
                    <div class="text-success fw-bold text-uppercase mb-1">
                        <i class="fas fa-check-circle me-2"></i>Denda Lunas
                    </div>
                    <div class="h3 mb-0" style="color: #28a745;">
                        Rp {{ number_format($totalPaid ?? 0, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-left-warning shadow h-100">
                <div class="card-body">
                    <div class="text-warning fw-bold text-uppercase mb-1">
                        <i class="fas fa-exclamation-triangle me-2"></i>Terlambat
                    </div>
                    <div class="h3 mb-0" style="color: #ffc107;">
                        {{ $overdue ?? 0 }} Denda
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fines Table -->
    <div class="card shadow-lg">
        <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white;">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>Daftar Denda Anggota
            </h5>
        </div>
        <div class="card-body p-0">
            @if($fines->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4"><i class="fas fa-user me-2"></i>Anggota</th>
                                <th><i class="fas fa-book me-2"></i>Buku</th>
                                <th class="text-center"><i class="fas fa-money-bill-wave me-2"></i>Jumlah</th>
                                <th><i class="fas fa-comment me-2"></i>Alasan</th>
                                <th class="text-center"><i class="fas fa-calendar me-2"></i>Batas Bayar</th>
                                <th class="text-center"><i class="fas fa-tag me-2"></i>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fines as $fine)
                                <tr class="{{ $fine->status === 'paid' ? 'table-light' : '' }}">
                                    <td class="ps-4">
                                        <strong>{{ $fine->borrowing->user->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $fine->borrowing->user->email }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $fine->borrowing->book->title }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $fine->borrowing->book->author }}</small>
                                    </td>
                                    <td class="text-center">
                                        <h6 class="mb-0" style="color: {{ $fine->status === 'paid' ? '#28a745' : '#dc3545' }}; font-weight: bold;">
                                            Rp {{ number_format($fine->amount, 0, ',', '.') }}
                                        </h6>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $fine->reason }}</span>
                                    </td>
                                    <td class="text-center">
                                        {{ $fine->due_date->format('d/m/Y') }}
                                        @if($fine->isOverdue() && $fine->status !== 'paid')
                                            <br>
                                            <span class="badge bg-warning text-dark mt-1">
                                                <i class="fas fa-exclamation-triangle me-1"></i>OVERDUE
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($fine->status === 'paid')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>Sudah Dibayar
                                            </span>
                                        @elseif($fine->status === 'waiting_confirmation')
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-hourglass-end me-1"></i>Menunggu Konfirmasi
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times-circle me-1"></i>Belum Lunas
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.fines.show', $fine) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.fines.edit', $fine) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($fine->status === 'waiting_confirmation')
                                                <button type="button" class="btn btn-sm btn-success" title="Konfirmasi Pembayaran" data-bs-toggle="modal" data-bs-target="#confirmPaymentModal" data-fine-id="{{ $fine->id }}" data-member="{{ $fine->borrowing->user->name }}" data-amount="{{ $fine->amount }}">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @elseif($fine->status === 'pending')
                                                <form action="{{ route('admin.fines.markAsPaid', $fine) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-success" title="Tandai Lunas" onclick="return confirm('Tandai denda ini sebagai lunas?')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin.fines.destroy', $fine) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Hapus denda ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-smile-wink fa-3x text-success mb-3"></i>
                    <h5>Tidak Ada Denda</h5>
                    <p class="text-muted">Semua anggota perpustakaan tidak memiliki denda apapun</p>
                </div>
            @endif
        </div>
    </div>

</div>

<style>
    .border-left-danger {
        border-left: 5px solid #dc3545 !important;
    }
    .border-left-success {
        border-left: 5px solid #28a745 !important;
    }
    .border-left-warning {
        border-left: 5px solid #ffc107 !important;
    }
</style>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmPaymentModal" tabindex="-1" aria-labelledby="confirmPaymentLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="confirmPaymentLabel">
                    <i class="fas fa-check-circle me-2"></i>Konfirmasi Pembayaran Denda
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin anggota <strong id="memberName"></strong> telah membayar denda sebesar <strong id="fineAmount"></strong>?</p>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Setelah dikonfirmasi, denda akan ditandai sebagai "Sudah Dibayar"
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="confirmPaymentForm" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i>Ya, Konfirmasi Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const confirmPaymentModal = document.getElementById('confirmPaymentModal');
    confirmPaymentModal.addEventListener('show.bs.modal', function(e) {
        const button = e.relatedTarget;
        const fineId = button.getAttribute('data-fine-id');
        const memberName = button.getAttribute('data-member');
        const amount = button.getAttribute('data-amount');

        // Update modal content
        document.getElementById('memberName').textContent = memberName;
        document.getElementById('fineAmount').textContent = 'Rp' + parseInt(amount).toLocaleString('id-ID');

        // Update form action
        const form = document.getElementById('confirmPaymentForm');
        form.action = `/admin/fines/${fineId}/mark-as-paid`;
    });
});
</script>
@endsection