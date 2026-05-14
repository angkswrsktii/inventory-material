<!DOCTYPE html>
<html lang="id">
<head>
    <title>Kartu Stok — {{ $material->name }}</title>
    <!-- CSS diabaikan agar singkat, gunakan style bawaan print kamu sebelumnya -->
    <style> body { font-family: Arial, sans-serif; font-size:12px; padding:20px; } table { width:100%; border-collapse:collapse; margin-top: 15px;} th, td { border: 1px solid #ccc; padding: 6px; } th { background: #eee; } .text-right { text-align: right; } .in { color: green; } .out { color: red; } </style>
</head>
<body onload="window.print()">
    <h2 style="text-align: center;">KARTU STOK MATERIAL</h2>
    <h3 style="text-align: center; margin-bottom: 20px;">{{ strtoupper($material->name) }} ({{ $material->code }})</h3>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Tipe Dokumen</th>
                <th>Keterangan / Notes</th>
                <th class="text-right">Masuk</th>
                <th class="text-right">Keluar</th>
                <th class="text-right">Saldo Terakhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stockCards as $sc)
            <tr>
                <td>{{ $sc->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ class_basename($sc->reference_type) }}</td>
                <td>{{ $sc->notes ?: '-' }}</td>
                <td class="text-right in">{{ $sc->type == 'in' ? '+'.number_format($sc->quantity, 2) : '-' }}</td>
                <td class="text-right out">{{ $sc->type == 'out' ? '-'.number_format($sc->quantity, 2) : '-' }}</td>
                <td class="text-right" style="font-weight: bold;">{{ number_format($sc->balance, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>