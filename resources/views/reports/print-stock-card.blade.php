<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Stok — {{ $material->name }}</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: Arial, sans-serif; font-size:12px; color:#1a1a1a; background:#fff; padding:20px; }
        .header { text-align:center; border-bottom:3px solid #1a1a1a; padding-bottom:12px; margin-bottom:16px; }
        .header h1 { font-size:18px; font-weight:800; letter-spacing:1px; text-transform:uppercase; }
        .header h2 { font-size:14px; font-weight:600; margin-top:4px; }
        .header p  { font-size:11px; color:#666; margin-top:2px; }
        .info-grid { display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:16px; border:1px solid #ccc; padding:12px; }
        .info-row  { display:flex; gap:8px; }
        .info-label { font-weight:600; min-width:120px; color:#555; }
        table { width:100%; border-collapse:collapse; margin-bottom:12px; }
        th { background:#1a1a1a; color:#fff; padding:8px 10px; text-align:left; font-size:11px; letter-spacing:0.5px; }
        td { padding:7px 10px; border-bottom:1px solid #e5e5e5; vertical-align:middle; }
        tr:nth-child(even) td { background:#f9f9f9; }
        .text-right { text-align:right; }
        .text-center { text-align:center; }
        .in  { color:#059669; font-weight:600; }
        .out { color:#dc2626; font-weight:600; }
        .bal { font-weight:700; }
        .footer { margin-top:30px; display:grid; grid-template-columns:1fr 1fr 1fr; gap:20px; }
        .sign-box { text-align:center; }
        .sign-line { border-bottom:1px solid #1a1a1a; margin-bottom:6px; height:50px; }
        .sign-label { font-size:11px; color:#555; }
        @media print {
            body { padding:10px; }
            .no-print { display:none; }
        }
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
    <h1>KARTU STOK RAW MATERIAL</h1>
    <h2>{{ strtoupper($material->name) }}</h2>
    <p>Dicetak: {{ now()->format('d M Y, H:i') }}</p>
</div>

<div class="info-grid">
    <div>
        <div class="info-row"><span class="info-label">Kode Barang</span><span>: {{ $material->code }}</span></div>
        <div class="info-row"><span class="info-label">Nama Material</span><span>: {{ $material->name }}</span></div>
        <div class="info-row"><span class="info-label">Spesifikasi</span><span>: {{ $material->specification ?: '-' }}</span></div>
    </div>
    <div>
        <div class="info-row"><span class="info-label">Satuan</span><span>: {{ $material->unit }}</span></div>
        <div class="info-row"><span class="info-label">Supplier</span><span>: {{ $material->supplier ?: '-' }}</span></div>
        <div class="info-row"><span class="info-label">Stok Saat Ini</span><span>: <strong>{{ number_format($material->current_stock, 2) }} {{ $material->unit }}</strong></span></div>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Keterangan</th>
            <th>No. Referensi</th>
            <th class="text-right">Masuk</th>
            <th class="text-right">Keluar</th>
            <th class="text-right">Saldo</th>
        </tr>
    </thead>
    <tbody>
        @forelse($stockCards as $i => $sc)
        <tr>
            <td class="text-center">{{ $i + 1 }}</td>
            <td>{{ $sc->transaction_date->format('d/m/Y') }}</td>
            <td>{{ $sc->source ?: ($sc->type === 'in' ? 'Penerimaan Barang' : 'Pengeluaran') }}</td>
            <td>{{ $sc->reference_no ?: '—' }}</td>
            <td class="text-right">
                @if($sc->quantity_in > 0) <span class="in">{{ number_format($sc->quantity_in,2) }}</span>
                @else —  @endif
            </td>
            <td class="text-right">
                @if($sc->quantity_out > 0) <span class="out">{{ number_format($sc->quantity_out,2) }}</span>
                @else — @endif
            </td>
            <td class="text-right bal">{{ number_format($sc->balance,2) }}</td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center" style="padding:20px; color:#999;">Belum ada transaksi</td></tr>
        @endforelse
    </tbody>
</table>

<div class="footer">
    <div class="sign-box">
        <div class="sign-line"></div>
        <div class="sign-label">Dibuat Oleh</div>
    </div>
    <div class="sign-box">
        <div class="sign-line"></div>
        <div class="sign-label">Diperiksa</div>
    </div>
    <div class="sign-box">
        <div class="sign-line"></div>
        <div class="sign-label">Disetujui</div>
    </div>
</div>

</body>
</html>