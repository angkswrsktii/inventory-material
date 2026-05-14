<!DOCTYPE html>
<html lang="id">
<head>
    <title>Good Issue — {{ $goodIssue->gi_number }}</title>
    <style> body { font-family: Arial, sans-serif; font-size:12px; padding:20px; } table { width:100%; border-collapse:collapse; margin-top: 15px;} th, td { border: 1px solid #ccc; padding: 6px; } th { background: #eee; } .text-right { text-align: right; } </style>
</head>
<body onload="window.print()">
    <h2 style="text-align: center;">BUKTI PENGELUARAN MATERIAL (GOOD ISSUE)</h2>
    <h3 style="text-align: center; margin-bottom: 20px;">{{ $goodIssue->gi_number }}</h3>

    <div style="margin-bottom: 10px;">
        <strong>Tanggal:</strong> {{ $goodIssue->issue_date->format('d M Y') }} <br>
        <strong>Target Part:</strong> {{ $goodIssue->part->part_name ?? '-' }} <br>
        <strong>PIC:</strong> {{ $goodIssue->pic->name ?? '-' }} <br>
        <strong>Tujuan:</strong> {{ $goodIssue->purpose }}
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Material</th>
                <th>Nama Material</th>
                <th class="text-right">Qty Keluar</th>
                <th>Satuan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($goodIssue->items as $i => $item)
            <tr>
                <td style="text-align: center;">{{ $i + 1 }}</td>
                <td>{{ $item->material->code ?? '-' }}</td>
                <td>{{ $item->material->name ?? '-' }}</td>
                <td class="text-right" style="font-weight: bold; color: red;">{{ number_format($item->quantity, 2) }}</td>
                <td>{{ $item->unit ?? 'Pcs' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table style="border: none; margin-top: 50px;">
        <tr style="border: none; text-align: center;">
            <td style="border: none;">Dibuat Oleh,<br><br><br><br>({{ $goodIssue->issuer->name ?? '..................' }})</td>
            <td style="border: none;">Diterima Oleh,<br><br><br><br>({{ $goodIssue->receiver->name ?? '..................' }})</td>
        </tr>
    </table>
</body>
</html>