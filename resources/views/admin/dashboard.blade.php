@extends('admin.admin')

@section('content')
<div class="container-fluid py-4">

    {{-- INFO CARD --}}
    <div class="row g-3 mb-4 text-white">
        <div class="col-md-4">
            <div class="card bg-primary border-0 shadow-sm p-3">
                <small>Omzet Hari Ini</small>
                <h3 class="fw-bold mb-0">
                    Rp{{ number_format($totalOmzet, 0, ',', '.') }}
                </h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success border-0 shadow-sm p-3">
                <small>Total Pengunjung</small>
                <h3 class="fw-bold mb-0">
                    {{ $totalPengunjung }} Meja
                </h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning border-0 shadow-sm p-3">
                <small>Meja Aktif</small>
                <h3 class="fw-bold mb-0">
                    {{ $activeTables }} Meja
                </h3>
            </div>
        </div>
    </div>

    {{-- TABEL RESERVASI --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">Daftar Reservasi Masuk (Aktif)</h5>
            <a href="{{ route('admin.history') }}"
               class="btn btn-dark btn-sm px-3 shadow-sm">
                Lihat Rekap History
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-3">Meja</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($reservations as $r)
                        <tr>
                            <td class="ps-3">Meja {{ $r->table_id }}</td>
                            <td class="text-uppercase">{{ $r->customer_name }}</td>
                            <td class="fw-bold">
                                Rp{{ number_format($r->total, 0, ',', '.') }}
                            </td>
                            <td>
                                <span class="badge bg-success">PAID</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">

                                    {{-- DETAIL --}}
                                    <button type="button"
                                            class="btn btn-sm btn-info text-white px-3"
                                            data-bs-toggle="modal"
                                            data-bs-target="#detail{{ $r->id }}">
                                        Detail
                                    </button>

                                    {{-- SELESAI --}}
                                    <form action="{{ route('admin.resetTable', $r->table_id) }}"
                                          method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="btn btn-sm btn-outline-success">
                                            Selesai
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5"
                                class="text-center py-5 text-muted fst-italic">
                                Tidak ada pesanan aktif saat ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

{{-- ================= MODAL DETAIL ================= --}}
@foreach($reservations as $r)
<div class="modal fade"
     id="detail{{ $r->id }}"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-dark text-white border-0">
                <h5 class="modal-title">
                    Rincian Pesanan - {{ $r->customer_name }}
                </h5>
                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-0">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Menu</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end pe-3">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($r->reservationDetails as $item)
                        <tr>
                            <td class="ps-3">
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
                    <tfoot class="table-light fw-bold">
                        <tr>
                            <td colspan="2" class="ps-3">
                                Total Pembayaran
                            </td>
                            <td class="text-end pe-3 text-primary">
                                Rp{{ number_format($r->total, 0, ',', '.') }}
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

@endsection
