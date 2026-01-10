@extends('admin.admin')

@section('content')
<div class="container py-4">

    {{-- HEADER: Sama kayak halaman Omzet --}}
    <div class="mb-4">
        <h2 class="fw-bold mb-0">Daftar Meja Aktif</h2>
        <p class="text-muted small">Manajemen operasional dan status meja real-time</p>
    </div>

    {{-- FILTER BOX: Sama kayak halaman Omzet --}}
    <div class="card border-0 shadow-sm p-3 mb-4 bg-white">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">RENTANG WAKTU</label>
                <select class="form-select form-select-sm">
                    <option value="daily">Harian</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">PILIH TANGGAL</label>
                <input type="date" class="form-control form-control-sm" value="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary btn-sm px-3 shadow-sm">Filter Otomatis</button>
            </div>
        </div>
    </div>

    {{-- TABEL MINIMALIS: Waktu, Meja, Aksi --}}
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead class="table-light border-bottom">
                    <tr class="text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">
                        <th class="py-3 text-muted" style="width: 20%;">Waktu</th>
                        <th class="py-3 text-muted" style="width: 50%;">Meja</th>
                        <th class="py-3 text-muted" style="width: 30%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tables as $table)
                    <tr>
                        <td class="text-muted small">
                            {{ $table->updated_at->format('H:i') }} WIB
                        </td>
                        <td>
                            <div class="fw-bold text-dark">MEJA {{ $table->table_number }}</div>
                            <span class="badge {{ $table->status == 'occupied' ? 'bg-danger' : 'bg-success' }} " style="font-size: 0.65rem;">
                                {{ $table->status == 'occupied' ? 'TERISI' : 'KOSONG' }}
                            </span>
                        </td>
                        <td>
                            @if($table->status == 'occupied')
                                <button class="btn btn-sm btn-outline-danger px-3 fw-bold" style="font-size: 0.7rem;">
                                    SELESAIKAN
                                </button>
                            @else
                                <button class="btn btn-sm btn-primary px-3 fw-bold" style="font-size: 0.7rem;">
                                    BUKA MEJA
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-5 text-muted">
                            Data tidak ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection