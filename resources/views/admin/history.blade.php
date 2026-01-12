@extends('admin.admin')

@section('content')
<div class="container py-4">

    {{-- HEADER --}}
    <div class="mb-4">
        <h2 class="fw-bold mb-0 text-uppercase" style="letter-spacing: 1px;">
            {{ request('view') == 'summary' ? 'Kesimpulan Omzet' : 'Semua Transaksi' }}
        </h2>
        <p class="text-muted small">Data operasional sistem Kedai Admin (Real-time Sync)</p>
    </div>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- FILTER --}}
    <div class="card border-0 shadow-sm p-3 mb-4 bg-white">
        <form action="{{ route('admin.history') }}" method="GET" class="row g-3 align-items-end" id="filterForm">
            <input type="hidden" name="view" value="{{ request('view', 'all') }}">

            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted text-uppercase">Rentang Waktu</label>
                <select name="filter" id="filterSelect" class="form-select form-select-sm shadow-none" onchange="this.form.submit()">
                    <option value="daily" {{ $filter == 'daily' ? 'selected' : '' }}>Harian</option>
                    <option value="weekly" {{ $filter == 'weekly' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="monthly" {{ $filter == 'monthly' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="yearly" {{ $filter == 'yearly' ? 'selected' : '' }}>Tahun Ini</option>
                </select>
            </div>

            <div class="col-md-3 {{ $filter != 'daily' ? 'd-none' : '' }}" id="dateWrapper">
                <label class="form-label small fw-bold text-muted text-uppercase">Pilih Tanggal</label>
                <input type="date" name="date" class="form-control form-control-sm shadow-none" value="{{ $date }}" onchange="this.form.submit()">
            </div>

            <div class="col-md-2">
                <a href="{{ route('admin.history', ['view' => request('view', 'all')]) }}" class="btn btn-sm btn-light border text-muted w-100">
                    <i class="bi bi-arrow-clockwise"></i> Reset Filter
                </a>
            </div>
        </form>
    </div>

    {{-- 1. TAMPILAN KESIMPULAN OMZET --}}
    @if(request('view') == 'summary')
    <div class="row g-3">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm bg-primary text-white p-4">
                <p class="mb-0 opacity-75 small text-uppercase fw-bold">Total Pendapatan</p>
                <h2 class="fw-bold mb-0">Rp{{ number_format($totalOmzet, 0, ',', '.') }}</h2>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm bg-success text-white p-4">
                <p class="mb-0 opacity-75 small text-uppercase fw-bold">Transaksi Berhasil</p>
                <h2 class="fw-bold mb-0">{{ $totalPengunjung }} Meja</h2>
            </div>
        </div>
    </div>
    @endif

    {{-- 2. TAMPILAN SEMUA TRANSAKSI --}}
    @if(request('view') == 'all')
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light border-bottom">
                    <tr>
                        <th class="ps-4 py-3 small text-muted">PELANGGAN</th>
                        <th class="py-3 small text-muted">MENU DIPESAN</th>
                        <th class="py-3 small text-muted">TOTAL TAGIHAN</th>
                        <th class="py-3 small text-muted text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history as $h)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark text-uppercase" style="font-size: 0.85rem;">{{ $h->customer_name }}</div>
                            <small class="text-muted">
                                Meja {{ $h->table_id }} • {{ $h->created_at->format('d/m/Y | H:i') }} WIB
                            </small>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($h->reservationDetails as $item)
                                <span class="badge bg-light text-dark border fw-normal" style="font-size: 0.75rem;">
                                    {{ $item->quantity }}x {{ $item->menu->name ?? 'Menu' }}
                                </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="fw-bold text-success" style="font-size: 1.1rem;">
                            Rp{{ number_format($h->total, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            {{-- Form Hapus Permanen --}}
                            <form action="{{ route('admin.history.destroy', $h->id) }}" method="POST"
                                onsubmit="return confirm('Data akan dihapus permanen. Lanjutkan?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger px-3">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted fst-italic">
                            Belum ada riwayat transaksi untuk periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterSelect = document.getElementById('filterSelect');
        const dateWrapper = document.getElementById('dateWrapper');

        // Memastikan visibilitas input tanggal sinkron saat dropdown diubah
        filterSelect.addEventListener('change', function() {
            if (this.value !== 'daily') {
                dateWrapper.classList.add('d-none');
            } else {
                dateWrapper.classList.remove('d-none');
            }
        });
    });
</script>
@endsection