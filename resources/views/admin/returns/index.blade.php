@extends('admin.layout')

@section('content')
<div class="container-fluid px-4 py-8">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 d-inline-block mb-0">
                <i class="fas fa-undo text-warning me-2"></i>Manajemen Pengembalian Buku
            </h1>
            <p class="text-muted mt-2 mb-0">Kelola pengembalian buku dari anggota perpustakaan</p>
        </div>
        <a href="{{ route('admin.returns.create') }}" class="btn btn-warning">
            <i class="fas fa-plus me-2"></i>Catat Pengembalian
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary">
                <div class="card-body">
                    <div class="text-primary font-weight-bold text-uppercase mb-1">Total Pengembalian</div>
                    <div class="h3 mb-0">{{ $returns->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success">
                <div class="card-body">
                    <div class="text-success font-weight-bold text-uppercase mb-1">Kondisi Baik</div>
                    <div class="h3 mb-0">{{ $returns->where('book_condition', 'good')->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning">
                <div class="card-body">
                    <div class="text-warning font-weight-bold text-uppercase mb-1">Rusak</div>
                    <div class="h3 mb-0">{{ $returns->where('book_condition', 'damaged')->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-danger">
                <div class="card-body">
                    <div class="text-danger font-weight-bold text-uppercase mb-1">Hilang</div>
                    <div class="h3 mb-0">{{ $returns->where('book_condition', 'lost')->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pengembalian Buku</h6>
        </div>
        <div class="card-body">
            @if($returns->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Anggota</th>
                                <th>Buku</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Kembali</th>
                                <th>Kondisi</th>
                                <th>Petugas</th>
                                <th>Catatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($returns as $return)
                            <tr>
                                <td>
                                    <strong>{{ $return->borrowing->user->name }}</strong><br>
                                    <small class="text-muted">{{ $return->borrowing->user->username }}</small>
                                </td>
                                <td>
                                    {{ $return->borrowing->book->title }}<br>
                                    <small class="text-muted">ISBN: {{ $return->borrowing->book->isbn }}</small>
                                </td>
                                <td>{{ $return->borrowing->borrow_date->format('d/m/Y') }}</td>
                                <td>
                                    <strong>{{ $return->return_date->format('d/m/Y H:i') }}</strong>
                                </td>
                                <td>
                                    @if($return->book_condition === 'good')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>Baik
                                        </span>
                                    @elseif($return->book_condition === 'damaged')
                                        <span class="badge bg-warning">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Rusak
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle me-1"></i>Hilang
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($return->staff)
                                        {{ $return->staff->name }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($return->notes)
                                        <small>{{ Str::limit($return->notes, 30) }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.returns.show', $return) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.returns.destroy', $return) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Hapus record pengembalian ini?')">
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
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5>Belum Ada Pengembalian</h5>
                    <p class="text-muted">Tidak ada catatan pengembalian buku saat ini</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .border-left-primary {
        border-left: 5px solid #004085 !important;
    }
    .border-left-success {
        border-left: 5px solid #28a745 !important;
    }
    .border-left-warning {
        border-left: 5px solid #ffc107 !important;
    }
    .border-left-danger {
        border-left: 5px solid #dc3545 !important;
    }
</style>
@endsection
