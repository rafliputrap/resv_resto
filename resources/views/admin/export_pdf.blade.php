<!DOCTYPE html>
<html>
<head>
    <title>Laporan Hafa Warehouse</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN TRANSAKSI HAFA WAREHOUSE</h2>
        <p>Periode: {{ ucfirst($filter) }} ({{ $date }})</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>TANGGAL</th>
                <th>PELANGGAN</th>
                <th>MEJA</th>
                <th>MENU</th>
                <th>TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                <td>{{ $row->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ strtoupper($row->customer_name) }}</td>
                <td>Meja {{ $row->table_id }}</td>
                <td>
                    @foreach($row->reservationDetails as $detail)
                        {{ $detail->quantity }}x {{ $detail->menu->name ?? 'Menu' }}<br>
                    @endforeach
                </td>
                <td>Rp{{ number_format($row->total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>