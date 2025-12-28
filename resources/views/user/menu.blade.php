<h2>Menu Meja {{ $table->table_number }}</h2>

<form method="POST" action="/order">
@csrf
<input type="hidden" name="table_id" value="{{ $table->id }}">

@foreach($menus as $menu)
    <p>
        {{ $menu->name }} - Rp{{ number_format($menu->price) }}
        <input type="number" name="menu[{{ $menu->id }}]" min="0" value="0">
    </p>
@endforeach

<button type="submit">Pesan</button>
</form>
