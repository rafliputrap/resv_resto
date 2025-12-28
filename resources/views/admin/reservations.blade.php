<h2>Reservasi</h2>

<table border="1">
<tr>
    <th>Meja</th>
    <th>Nama</th>
    <th>Total</th>
    <th>Status</th>
</tr>

@foreach($data as $r)
<tr>
    <td>{{ $r->table->table_number }}</td>
    <td>{{ $r->customer_name }}</td>
    <td>Rp{{ number_format($r->total) }}</td>
    <td>{{ $r->status }}</td>
</tr>
@endforeach
</table>
