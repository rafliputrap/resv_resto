@extends('admin.admin')

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-sm p-4">
        <h2 class="fw-bold mb-4">Detail Pesanan Meja #{{ $reservation->table_id }}</h2>

        <div class="row mb-4 bg-light p-3 rounded">
            <div class="col-md-6">
                <small class="text-muted">Nama Pelanggan:</small>
                <h5 class="fw-bold">{{ $reservation->customer_name }}</h5>
            </div>
            <div class="col-md-6 text-md-end">
                <small class="text-muted">Nomor WhatsApp:</small>
                <h5 class="text-success fw-bold">{{ $reservation->phone }}</h5>
            </div>
        </div>

        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Menu</th>
                    <th class="text-center">Qty</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                {{-- Kita langsung looping dari relasi yang udah kita 'with' di controller tadi --}}
                @forelse($reservation->reservationDetails as $detail)
                <tr>
                    {{-- Ambil nama menu dari relasi menu --}}
                    <td class="fw-bold">{{ $detail->menu->name }}</td>
                    <td class="text-center">{{ $detail->quantity }}x</td>
                    <td class="text-end">
                        Rp{{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center py-4 text-danger">
                        <b>Detail pesanan tidak ditemukan!</b>
                    </td>
                </tr>
                @endforelse
            </tbody>
            <tfoot class="table-light">
                <tr>
                    <th colspan="2" class="text-end">TOTAL TAGIHAN:</th>
                    <th class="text-end text-primary h5 fw-bold">Rp{{ number_format($reservation->total, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mt-3">Kembali</a>
    </div>
</div>
@endsection