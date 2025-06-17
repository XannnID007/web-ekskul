@extends('layouts.admin')

@section('title', 'Monitor Pendaftaran')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 text-gray-800">Monitor Pendaftaran</h1>
                <p class="text-muted mb-0">Pantau status pendaftaran ekstrakurikuler</p>
            </div>
        </div>

        <!-- Stats Summary -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stats-card primary">
                    <div class="text-center">
                        <div class="h3">{{ $stats['total'] }}</div>
                        <div>Total Pendaftaran</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card warning">
                    <div class="text-center">
                        <div class="h3">{{ $stats['pending'] }}</div>
                        <div>Menunggu</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card success">
                    <div class="text-center">
                        <div class="h3">{{ $stats['approved'] }}</div>
                        <div>Disetujui</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card info">
                    <div class="text-center">
                        <div class="h3">{{ $stats['rejected'] }}</div>
                        <div>Ditolak</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved
                                </option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Dari Tanggal</label>
                            <input type="date" name="start_date" class="form-control"
                                value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Sampai Tanggal</label>
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter me-1"></i>Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Pendaftaran Table -->
        <div class="card">
            <div class="card-body">
                @if ($pendaftaran->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Siswa</th>
                                    <th>Ekstrakurikuler</th>
                                    <th>Pembina</th>
                                    <th>Status</th>
                                    <th>Skor Rekomendasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pendaftaran as $item)
                                    <tr>
                                        <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                                        <td>
                                            <div class="fw-bold">{{ $item->siswa->user->name }}</div>
                                            <small class="text-muted">{{ $item->siswa->kelas }}</small>
                                        </td>
                                        <td>{{ $item->ekstrakurikuler->nama_ekskul }}</td>
                                        <td>{{ $item->ekstrakurikuler->pembina->name }}</td>
                                        <td>
                                            @if ($item->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($item->status == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->skor_rekomendasi)
                                                <span
                                                    class="badge bg-{{ $item->skor_rekomendasi >= 80 ? 'success' : ($item->skor_rekomendasi >= 70 ? 'warning' : 'danger') }} fs-6">
                                                    {{ number_format($item->skor_rekomendasi, 1) }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $pendaftaran->links() }}
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                        <h5>Tidak Ada Data</h5>
                        <p class="text-muted">Data pendaftaran tidak ditemukan</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
