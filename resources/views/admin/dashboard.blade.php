@extends('admin.admin')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4">Daftar Reservasi Masuk</h2>
    
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm rounded-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-3">Meja</th>
                        <th>Nama Pelanggan</th>
                        <th>Total</th>
                        <th>Status Pesanan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservations as $r)
                    <tr>
                        <td class="ps-3 fw-bold">Meja {{ $r->table_id }}</td>
                        <td class="text-uppercase">{{ $r->customer_name }}</td>
                        <td>Rp{{ number_format($r->total, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $r->status == 'pending' ? 'bg-warning text-dark' : ($r->status == 'confirmed' ? 'bg-success' : 'bg-danger') }}">
                                {{ strtoupper($r->status) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('admin.reservation.show', $r->id) }}" class="btn btn-sm btn-info text-white px-3">Detail</a>
                                
                                @if($r->status == 'pending')
                                <form action="{{ route('admin.reservation.status', $r->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="confirmed">
                                    <button type="submit" class="btn btn-sm btn-success px-3">Terima</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection