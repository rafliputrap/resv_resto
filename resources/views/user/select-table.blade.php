<!DOCTYPE html>
<html>
<head>
    <title>Pilih Meja</title>
    <link rel="stylesheet" href="{{ asset('css/table-layout.css') }}">
</head>
<body>

<h2>Pilih Meja</h2>

<form method="POST" action="/select-table">
@csrf

<div class="denah">
@foreach($tables as $t)
    <button
        type="submit"
        name="table_id"
        value="{{ $t->id }}"
        class="meja {{ $t->status }}"
        data-x="{{ optional($t->layout)->x ?? 0 }}"
        data-y="{{ optional($t->layout)->y ?? 0 }}"
    >
        {{ $t->table_number }}
    </button>
@endforeach
</div>

</form>

<script>
document.querySelectorAll('.meja').forEach(btn => {
    btn.style.left = btn.dataset.x + 'px';
    btn.style.top  = btn.dataset.y + 'px';
});
</script>

</body>
</html>
