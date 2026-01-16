<!DOCTYPE html>
<html>
<head>
    <title>Reservasi Meja</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
</head>
<body>
<h1>Reservasi Meja: {{ $table->table_number }}</h1>

@if(session('success'))
<p style="color:green;">{{ session('success') }}</p>
@endif

<form action="{{ url('/reservasi') }}" method="POST">
    @csrf
    <input type="hidden" name="table_id" value="{{ $table->id }}">
    <input type="text" name="customer_name" placeholder="Nama" required>
    <input type="text" name="phone" placeholder="No HP" required>
    <button type="submit">Reservasi</button>
</form>

<h2>Menu</h2>
<ul>
@foreach($menus as $menu)
    <li>{{ $menu->name }} - Rp {{ number_format($menu->price,0,',','.') }}
        @if($menu->description) ({{ $menu->description }}) @endif
    </li>
@endforeach
</ul>
</body>
</html>
