@extends('admin.admin')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">History & Laporan</h2>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-dark shadow-sm">Dashboard</a>
    </div>

    <div class="card border-0 shadow-sm p-3 mb-4">
        <form action="{{ route('admin.history') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-bold">Rentang Waktu</label>
                <select name="filter" id="filterSelect" class="form-select">
                    <option value="daily" @selected($filter=='daily' )>Harian</option>
                    <option value="weekly" @selected($filter=='weekly' )>Minggu Ini</option>
                    <option value="monthly" @selected($filter=='monthly' )>Bulan Ini</option>
                    <option value="yearly" @selected($filter=='yearly' )>Tahun Ini</option>
                </select>
            </div>
            <div class="col-md-3" id="dateWrapper" style="display: {{ $filter == 'daily' ? 'block' : 'none' }};">
                <label class="form-label small fw-bold">Pilih Tanggal</label>
                <input type="date" name="date" class="form-control" value="{{ $date }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-dark w-100 fw-bold">FILTER</button>
            </div>
        </form>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm bg-primary text-white p-3">
                <p class="mb-0 opacity-75 small text-uppercase">Total Omzet</p>
                <h3 class="fw-bold mb-0">Rp{{ number_format($totalOmzet, 0, ',', '.') }}</h3>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm bg-success text-white p-3">
                <p class="mb-0 opacity-75 small text-uppercase">Total Transaksi Selesai</p>
                <h3 class="fw-bold mb-0">{{ $totalPengunjung }} Meja</h3>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary">
                    <tr>
                        <th class="ps-3">Waktu</th>
                        <th>Meja</th>
                        <th>Pelanggan</th>
                        <th>Durasi</th>
                        <th>Total</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history as $h)
                    <tr>
                        <td class="ps-3">
                            <small class="text-muted d-block">{{ $h->created_at->format('d/m/Y') }}</small>
                            <strong>{{ $h->created_at->format('H:i') }}</strong>
                        </td>
                        <td class="fw-bold text-primary">Meja {{ $h->table_id }}</td>
                        <td class="text-uppercase small">{{ $h->customer_name }}</td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $h->duration }}
                            </span>
                        </td>
                        <td class="fw-bold text-success">
                            Rp{{ number_format($h->total, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">

                                {{-- DETAIL --}}
                                <button type="button"
                                    class="btn btn-sm btn-info text-white px-3"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalHistory{{ $h->id }}">
                                    Detail
                                </button>

                                {{-- HAPUS --}}
                                <form action="{{ route('admin.history.delete', $h->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Hapus data ini dari history?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="btn btn-sm btn-outline-danger px-3">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6"
                            class="text-center py-5 text-muted">
                            Belum ada data tersedia.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- ================= MODAL DETAIL HISTORY ================= --}}
            @foreach($history as $h)
            <div class="modal fade"
                id="modalHistory{{ $h->id }}"
                tabindex="-1"
                aria-hidden="true">

                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content border-0 shadow-lg">

                        <div class="modal-header bg-dark text-white border-0">
                            <h5 class="modal-title fw-bold">
                                Rincian Pesanan - {{ $h->customer_name }}
                            </h5>
                            <button type="button"
                                class="btn-close btn-close-white"
                                data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body p-0">
                            <table class="table mb-0 text-start">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3 py-3">Menu</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end pe-3">Subtotal</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($h->reservationDetails as $item)
                                    <tr>
                                        <td class="ps-3 py-2">
                                            {{ $item->menu->name ?? 'Menu' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $item->quantity }}x
                                        </td>
                                        <td class="text-end pe-3">
                                            Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>

                                <tfoot class="table-light fw-bold border-top">
                                    <tr>
                                        <td colspan="2" class="ps-3 py-3 text-uppercase">
                                            Total Pembayaran
                                        </td>
                                        <td class="text-end pe-3 py-3 text-success h5 mb-0">
                                            Rp{{ number_format($h->total, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="modal-footer border-0">
                            <button type="button"
                                class="btn btn-secondary w-100"
                                data-bs-dismiss="modal">
                                Tutup
                            </button>
                        </div>

                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    document.getElementById('filterSelect').addEventListener('change', function() {
        document.getElementById('dateWrapper').style.display = (this.value === 'daily') ? 'block' : 'none';
    });
</script>
@endsection