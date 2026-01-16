<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden p-8 text-center" style="position: relative; z-index: 10;">
        <div class="mb-4 inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-gray-800 mb-2">Terima Kasih!</h1>
        <p class="text-gray-600 mb-6">Pembayaran Anda telah berhasil kami terima.</p>

        <div class="bg-blue-50 border-2 border-dashed border-blue-200 rounded-xl p-6 mb-6">
            <p class="text-sm text-blue-600 uppercase tracking-widest font-semibold mb-1">Nomor Pesanan Anda</p>
            <h2 class="text-4xl font-black text-blue-800 tracking-tighter">#{{ $res->order_number }}</h2>
        </div>

        <div class="text-left space-y-3 mb-8 text-sm border-t pt-6">
            <div class="flex justify-between">
                <span class="text-gray-500">Pelanggan:</span>
                <span class="font-semibold text-gray-800 text-uppercase">{{ $res->customer_name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Meja:</span>
                <span class="font-semibold text-gray-800">Meja {{ $res->table_id }}</span>
            </div>
            <div class="flex justify-between text-lg border-t pt-3 font-bold">
                <span class="text-gray-800">Total Bayar:</span>
                <span class="text-green-600 font-bold">Rp{{ number_format($res->total, 0, ',', '.') }}</span>
            </div>
        </div>

        <p class="text-xs text-gray-400 mb-6 italic">*Silakan tunjukkan layar ini kepada pelayan jika diperlukan.</p>

        {{-- AREA TOMBOL --}}
        <div class="mt-8 space-y-4">
            {{-- TOMBOL 1: Cuma buat nambah pesanan (Meja tetap Terisi/Occupied) --}}
            <a href="{{ route('user.menu') }}"
                class="block w-full bg-white border-2 border-blue-600 text-blue-600 font-bold py-4 rounded-xl shadow-sm text-center hover:bg-blue-50 transition">
                TAMBAH PESANAN LAIN
            </a>

            {{-- TOMBOL 2: Selesaikan Sesi (Meja jadi Kosong/Available) --}}
            <form action="{{ route('customer.finishTable', $res->table_id) }}" method="POST">
                @csrf
                <button type="submit"
                    class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl transition shadow-lg text-center">
                    SELESAIKAN & KOSONGKAN MEJA
                </button>
            </form>

            <p class="mt-4 text-[10px] text-gray-400 uppercase tracking-widest text-center">
                Pilih "Selesaikan" jika Anda sudah ingin meninggalkan meja.
            </p>
        </div>
    </div>
</body>

</html>