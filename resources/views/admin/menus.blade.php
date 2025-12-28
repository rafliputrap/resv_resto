<h2>Daftar Menu</h2>

<ul>
@foreach($menus as $m)
    <li>{{ $m->name }} - Rp{{ number_format($m->price) }}</li>
@endforeach
</ul>
