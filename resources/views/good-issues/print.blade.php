<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Print GI — {{ $goodIssue->gi_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; padding: 20px; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #f4f4f4; border: 1px solid #ccc; padding: 8px; text-align: left; }
        td { border: 1px solid #ccc; padding: 8px; }
        .text-right { text-align: right; }
        .footer { margin-top: 40px; display: flex; justify-content: space-between; }
        .sign { width: 200px; text-align: center; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print"><button onclick="window.print()">Print Dokumen</button></div>
    
    <div class="header">
        <div>
            <h2>GOOD ISSUE (GI)</h2>
            <p>No: {{ $goodIssue->gi_number }}</p>
        </div>
        <div style="text-align: right;">
            <p>Tanggal: {{ $goodIssue->issue_date->format('d M Y') }}</p>
            <p>PIC: {{ $goodIssue->pic->name ?? '-' }}</p>
        </div>
    </div>

    <p><strong>Tujuan/Catatan:</strong> {{ $goodIssue->purpose }}</p>
    <p><strong>Target Part:</strong> {{ $goodIssue->part->part_name ?? '-' }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Material</th>
                <th>Kode</th>
                <th class="text-right">Qty</th>
            </tr>
        </thead>
        <tbody>
            @foreach($goodIssue->items as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->material->name ?? '-' }}</td>
                <td>{{ $item->material->code ?? '-' }}</td>
                <td class="text-right">{{ number_format($item->quantity, 2) }} {{ $item->unit }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="sign">
            <p>Dikeluarkan Oleh,</p><br><br><br>
            <p>({{ $goodIssue->issuer->name ?? '____' }})</p>
        </div>
        <div class="sign">
            <p>Penerima,</p><br><br><br>
            <p>(____________)</p>
        </div>
    </div>
</body>
</html>