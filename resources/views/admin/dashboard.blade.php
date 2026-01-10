@extends('admin.admin')

@section('content')
<style>
    /* Styling khusus konten Dashboard agar konsisten dengan template baru */
    .table-container {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .badge-paid {
        background-color: #d1e7dd;
        color: #0f5132;
        border: 1px solid #badbcc;
    }

    /* Penyesuaian jarak agar tidak mepet navbar */
    .dashboard-header {
        margin-bottom: 25px;
    }
</style>

<div class="dashboard-header">
    <h4 class="fw-bold mb-0">Dashboard Operasional</h4>
    <p class="text-muted small">Pantau reservasi dan status meja secara real-time</p>
</div>

{{-- TABEL TRANSAKSI AKTIF --}}
<div class="table-container">
    <div class="p-4 border-bottom bg-white d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0">Daftar Reservasi Aktif</h6>
        <span class="badge bg-primary px-3">Live Status</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4 py-3 text-muted small">NOMOR MEJA</th>
                    <th class="text-muted small">NAMA PELANGGAN</th>
                    <th class="text-muted small">TOTAL TAGIHAN</th>
                    <th class="text-muted small">STATUS</th>
                    <th class="text-center text-muted small">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $r)
                <tr>
                    <td class="ps-4 fw-bold text-dark">Meja {{ $r->table_id }}</td>
                    <td class="text-uppercase small fw-semibold">{{ $r->customer_name }}</td>
                    <td class="fw-bold">Rp{{ number_format($r->total, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge badge-paid px-3 text-uppercase" style="font-size: 10px;">PAID</span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <button class="btn btn-sm btn-outline-dark"
                                data-bs-toggle="modal" data-bs-target="#detail{{ $r->id }}">
                                <i class="fas fa-eye me-1"></i> Detail
                            </button>
                            <form action="{{ route('admin.tables.updateStatus', $r->table_id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success px-3 fw-bold">
                                    Selesai
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted fst-italic">
                        Tidak ada aktivitas di meja saat ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL DETAIL (Ditaruh di luar tabel agar tidak rusak layoutnya) --}}
@foreach($reservations as $r)
<div class="modal fade" id="detail{{ $r->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-light">
                <h6 class="modal-title fw-bold">RINCIAN PESANAN - MEJA {{ $r->table_id }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <table class="table mb-0">
                    <thead class="small text-muted">
                        <tr>
                            <th class="ps-3">MENU</th>
                            <th class="text-center">QTY</th>
                            <th class="text-end pe-3">SUBTOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($r->reservationDetails as $item)
                        <tr>
                            <td class="ps-3">{{ $item->menu->name ?? 'Menu' }}</td>
                            <td class="text-center">{{ $item->quantity }}x</td>
                            <td class="text-end pe-3">Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-light">
                        <tr class="fw-bold">
                            <td colspan="2" class="ps-3">TOTAL PEMBAYARAN</td>
                            <td class="text-end pe-3 text-primary">Rp{{ number_format($r->total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection