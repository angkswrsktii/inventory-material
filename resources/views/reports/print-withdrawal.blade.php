<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Pengambilan — {{ $withdrawalCard->document_no }}</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:Arial, sans-serif; font-size:12px; color:#1a1a1a; background:#fff; padding:20px; }
        .header { text-align:center; border-bottom:3px solid #1a1a1a; padding-bottom:12px; margin-bottom:16px; }
        .header h1 { font-size:18px; font-weight:800; letter-spacing:1px; text-transform:uppercase; }
        .header p  { font-size:11px; color:#666; margin-top:4px; }
        .doc-no { display:inline-block; background:#1a1a1a; color:#fff; padding:4px 16px; border-radius:4px; font-size:13px; font-weight:700; margin-top:6px; letter-spacing:1px; }
        .info-grid { display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:16px; border:1px solid #ccc; padding:12px; }
        .info-row  { display:flex; gap:8px; margin-bottom:4px; }
        .info-label { font-weight:600; min-width:110px; color:#555; }
        table { width:100%; border-collapse:collapse; margin-bottom:16px; }
        th { background:#1a1a1a; color:#fff; padding:8px 10px; text-align:left; font-size:11px; letter-spacing:0.5px; }
        td { padding:8px 10px; border-bottom:1px solid #e5e5e5; }
        tr:nth-child(even) td { background:#f9f9f9; }
        .text-right { text-align:right; }
        .text-center { text-align:center; }
        .out { color:#dc2626; font-weight:700; }
        .footer { margin-top:30px; display:grid; grid-template-columns:repeat(4,1fr); gap:16px; }
        .sign-box { text-align:center; }
        .sign-line { border-bottom:1px solid #1a1a1a; margin-bottom:6px; height:50px; }
        .sign-label { font-size:11px; color:#555; }
        .status-badge { display:inline-block; padding:3px 14px; border-radius:20px; font-size:11px; font-weight:700;
            background:#dcfce7; color:#166534; border:1px solid #86efac; }
        @media print { .no-print { display:none; } }
    </style>
</head>
<body>

<div class="no-print" style="margin-bottom:16px;">
    <button onclick="window.print()" style="padding:8px 20px; background:#1a1a1a; color:#fff; border:none; border-radius:6px; cursor:pointer; font-size:13px;">
        🖨️ Print / Save PDF
    </button>
    <button onclick="window.close()" style="padding:8px 16px; background:#eee; border:none; border-radius:6px; cursor:pointer; margin-left:8px; font-size:13px;">
        ✕ Tutup
    </button>
</div>

<div class="header">
    <h1>KARTU PENGAMBILAN MATERIAL</h1>
    <div class="doc-no">{{ $withdrawalCard->document_no }}</div>
    <p style="margin-top:6px;">Dicetak: {{ now()->format('d M Y, H:i') }}</p>
</div>

<div class="info-grid">
    <div>
        <div class="info-row"><span class="info-label">No. Dokumen</span><span>: {{ $withdrawalCard->document_no }}</span></div>
        <div class="info-row"><span class="info-label">Tanggal</span><span>: {{ $withdrawalCard->withdrawal_date->format('d M Y') }}</span></div>
        <div class="info-row"><span class="info-label">PIC</span><span>: {{ $withdrawalCard->pic }}</span></div>
        <div class="info-row"><span class="info-label">Status</span><span>: <span class="status-badge">{{ strtoupper($withdrawalCard->status) }}</span></span></div>
    </div>
    <div>
        <div class="info-row"><span class="info-label">Line Produksi</span><span>: {{ $withdrawalCard->line }}</span></div>
        <div class="info-row"><span class="info-label">Part Name</span><span>: {{ $withdrawalCard->part_name }}</span></div>
        <div class="info-row"><span class="info-label">Work Order</span><span>: {{ $withdrawalCard->work_order ?: '—' }}</span></div>
        <div class="info-row"><span class="info-label">Keterangan</span><span>: {{ $withdrawalCard->notes ?: '—' }}</span></div>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th class="text-center" style="width:40px;">No</th>
            <th>Kode Barang</th>
            <th>Nama Material</th>
            <th>Spesifikasi</th>
            <th>Satuan</th>
            <th class="text-right">Stok Sebelum</th>
            <th class="text-right">Jml Diambil</th>
            <th class="text-right">Stok Sesudah</th>
        </tr>
    </thead>
    <tbody>
        @foreach($withdrawalCard->items as $i => $item)
        <tr>
            <td class="text-center">{{ $i + 1 }}</td>
            <td>{{ $item->material->code ?? '-' }}</td>
            <td>{{ $item->material->name ?? '-' }}</td>
            <td>{{ $item->material->specification ?? '-' }}</td>
            <td class="text-center">{{ $item->material->unit ?? '-' }}</td>
            <td class="text-right">{{ number_format($item->stock_before, 2) }}</td>
            <td class="text-right"><span class="out">{{ number_format($item->quantity, 2) }}</span></td>
            <td class="text-right"><strong>{{ number_format($item->stock_after, 2) }}</strong></td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    <div class="sign-box">
        <div class="sign-line"></div>
        <div class="sign-label">Dibuat Oleh<br><strong>{{ $withdrawalCard->creator?->name ?? '—' }}</strong></div>
    </div>
    <div class="sign-box">
        <div class="sign-line"></div>
        <div class="sign-label">Bagian Gudang</div>
    </div>
    <div class="sign-box">
        <div class="sign-line"></div>
        <div class="sign-label">Supervisor</div>
    </div>
    <div class="sign-box">
        <div class="sign-line"></div>
        <div class="sign-label">Disetujui<br><strong>{{ $withdrawalCard->approver?->name ?? '—' }}</strong></div>
    </div>
</div>

</body>
</html>