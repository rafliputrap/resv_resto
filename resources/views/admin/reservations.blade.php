@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Daftar Reservasi Masuk</h2>

    {{-- Pesan Sukses setelah ACC/Tolak --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Meja</th>
                            <th>Nama Pelanggan</th>
                            <th>WhatsApp</th>
                            <th>Total Bayar</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservations as $res)
                        <tr>
                            <td class="fw-bold">#{{ $res->table_id }}</td>
                            <td>{{ $res->customer_name }}</td>
                            <td>
                                <a href="https://wa.me/{{ $res->phone }}" target="_blank" class="text-decoration-none">
                                    {{ $res->phone }} 📱
                                </a>
                            </td>
                            <td class="fw-bold">Rp{{ number_format($res->total, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge {{ $res->status == 'pending' ? 'bg-warning text-dark' : ($res->status == 'confirmed' ? 'bg-success' : 'bg-danger') }}">
                                    {{ strtoupper($res->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                {{-- Tombol Detail untuk melihat list makanan --}}
                                <a href="{{ route('admin.reservation.show', $res->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                    👁️ Detail
                                </a>

                                @if($res->status == 'pending')
                                    <form action="{{ route('admin.reservation.status', $res->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="confirmed">
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Terima pesanan ini?')">
                                            ✅ Terima
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.reservation.status', $res->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tolak pesanan ini?')">
                                            ❌ Tolak
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted small">Proses Selesai</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($reservations->isEmpty())
                <div class="text-center py-4">
                    <p class="text-muted">Belum ada data reservasi masuk.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection