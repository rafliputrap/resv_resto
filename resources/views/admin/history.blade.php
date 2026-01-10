@extends('admin.admin')

@section('content')
<div class="container py-4">

    {{-- HEADER --}}
    <div class="mb-4">
        <h2 class="fw-bold mb-0 text-uppercase" style="letter-spacing: 1px;">
            {{ request('view') == 'summary' ? 'Kesimpulan Omzet' : 'Semua Transaksi' }}
        </h2>
        <p class="text-muted small">Data operasional sistem Kedai Admin</p>
    </div>

    {{-- FILTER --}}
    <div class="card border-0 shadow-sm p-3 mb-4 bg-white">
        <form action="{{ route('admin.history') }}" method="GET" class="row g-3 align-items-end">
            <input type="hidden" name="view" value="{{ request('view') }}">
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">RENTANG WAKTU</label>
                <select name="filter" id="filterSelect" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="daily">Harian</option>
                    <option value="weekly">Minggu Ini</option>
                    <option value="monthly">Bulan Ini</option>
                    <option value="yearly">Tahun Ini</option>
                </select>
            </div>
            <div class="col-md-3 {{ $filter != 'daily' ? 'd-none' : '' }}" id="dateWrapper">
                <label class="form-label small fw-bold text-muted">PILIH TANGGAL</label>
                <input type="date" name="date" class="form-control form-control-sm" value="{{ $date }}" onchange="this.form.submit()">
            </div>
        </form>
    </div>

    {{-- 1. TAMPILAN KESIMPULAN OMZET (Hanya Ringkasan) --}}
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

    {{-- 2. TAMPILAN SEMUA TRANSAKSI (Tanpa Aksi) --}}
    @if(request('view') == 'all')
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light border-bottom">
                    <tr>
                        <th class="ps-4 py-3 small text-muted" style="width: 30%;">PELANGGAN</th>
                        <th class="py-3 small text-muted" style="width: 45%;">MENU DIPESAN</th>
                        <th class="py-3 small text-muted" style="width: 25%;">TOTAL TAGIHAN</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history as $h)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark text-uppercase" style="font-size: 0.85rem;">{{ $h->customer_name }}</div>
                            <small class="text-muted">Meja {{ $h->table_id }} • {{ $h->created_at->format('H:i') }} WIB</small>
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
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-5 text-muted fst-italic">
                            Belum ada riwayat transaksi.
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
        
        filterSelect.addEventListener('change', function() {
            dateWrapper.classList.toggle('d-none', this.value !== 'daily');
        });
    });
</script>
@endsection