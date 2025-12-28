<h2>Detail Pesanan</h2>

@foreach($menus as $menu)
    <p>
        {{ $menu->name }}
        x {{ $cart[$menu->id] }}
        = Rp{{ number_format($menu->price * $cart[$menu->id]) }}
    </p>
@endforeach

<h3>Total: Rp{{ number_format($total) }}</h3>

<a href="/payment">Lanjut Pembayaran</a>
