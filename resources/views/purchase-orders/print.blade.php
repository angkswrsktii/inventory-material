<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Purchase Order — {{ $purchaseOrder->document_no }}</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:Arial, sans-serif; font-size:12px; color:#1a1a1a; background:#fff; padding:20px; }
        .header { display:flex; justify-content:space-between; align-items:flex-start; border-bottom:3px solid #1a1a1a; padding-bottom:14px; margin-bottom:16px; }
        .company-name { font-size:20px; font-weight:800; letter-spacing:-0.5px; }
        .company-sub  { font-size:11px; color:#666; margin-top:3px; }
        .doc-info { text-align:right; }
        .doc-title { font-size:18px; font-weight:800; text-transform:uppercase; letter-spacing:1px; }
        .doc-no    { display:inline-block; background:#1a1a1a; color:#fff; padding:4px 16px; border-radius:4px; font-size:13px; font-weight:700; margin-top:6px; letter-spacing:1px; }
        .doc-date  { font-size:11px; color:#666; margin-top:4px; }
        .info-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px; }
        .info-box  { border:1px solid #ddd; border-radius:6px; padding:12px 14px; }
        .info-box h4 { font-size:10px; text-transform:uppercase; letter-spacing:1px; color:#888; margin-bottom:8px; }
        .info-row  { display:flex; gap:8px; margin-bottom:4px; font-size:12px; }
        .info-label{ font-weight:600; min-width:110px; color:#444; }
        .status-badge { display:inline-block; padding:3px 12px; border-radius:20px; font-size:11px; font-weight:700; }
        .status-draft     { background:#f3f4f6; color:#6b7280; }
        .status-sent      { background:#dbeafe; color:#1d4ed8; }
        .status-partial   { background:#fef3c7; color:#92400e; }
        .status-received  { background:#d1fae5; color:#065f46; }
        .status-cancelled { background:#fee2e2; color:#991b1b; }
        table { width:100%; border-collapse:collapse; margin-bottom:16px; }
        th { background:#1a1a1a; color:#fff; padding:9px 10px; text-align:left; font-size:11px; letter-spacing:0.5px; }
        td { padding:8px 10px; border-bottom:1px solid #e5e5e5; vertical-align:middle; }
        tr:nth-child(even) td { background:#f9f9f9; }
        .text-right { text-align:right; }
        .text-center { text-align:center; }
        tfoot td { background:#f3f4f6 !important; font-weight:700; border-top:2px solid #1a1a1a; }
        .notes-box { border:1px solid #ddd; border-radius:6px; padding:12px 14px; margin-bottom:20px; }
        .notes-box h4 { font-size:10px; text-transform:uppercase; letter-spacing:1px; color:#888; margin-bottom:6px; }
        .footer { display:grid; grid-template-columns:repeat(3,1fr); gap:20px; margin-top:30px; }
        .sign-box { text-align:center; }
        .sign-line { border-bottom:1px solid #1a1a1a; height:50px; margin-bottom:6px; }
        .sign-label { font-size:11px; color:#555; }
        .sign-name  { font-size:12px; font-weight:600; margin-top:4px; }
        @media print { .no-print { display:none; } body { padding:10px; } }
    </style>
</head>
<body>

<div class="no-print" style="margin-bottom:16px; display:flex; gap:10px;">
    <button onclick="window.print()" style="padding:8px 20px; background:#1a1a1a; color:#fff; border:none; border-radius:6px; cursor:pointer; font-size:13px;">
        🖨️ Print / Save PDF
    </button>
    <button onclick="window.close()" style="padding:8px 16px; background:#eee; border:none; border-radius:6px; cursor:pointer; font-size:13px;">
        ✕ Tutup
    </button>
</div>

<div class="header">
    <div>
        <div class="company-name">RAWMATPRO</div>
        <div class="company-sub">Sistem Manajemen Persediaan Raw Material</div>
        <div class="company-sub" style="margin-top:8px;">
            Dicetak oleh: {{ auth()->user()->name }} &nbsp;|&nbsp; {{ now()->format('d M Y, H:i') }}
        </div>
    </div>
    <div class="doc-info">
        <div class="doc-title">Purchase Order</div>
        <div class="doc-no">{{ $purchaseOrder->document_no }}</div>
        <div class="doc-date">Tgl. Order: {{ $purchaseOrder->order_date->format('d M Y') }}</div>
        <div style="margin-top:6px;">
            <span class="status-badge status-{{ $purchaseOrder->status }}">{{ $purchaseOrder->status_label }}</span>
        </div>
    </div>
</div>

<div class="info-grid">
    <div class="info-box">
        <h4>Informasi Supplier</h4>
        <div class="info-row"><span class="info-label">Nama Supplier</span><span>: {{ $purchaseOrder->supplier_name }}</span></div>
        <div class="info-row"><span class="info-label">Kontak</span><span>: {{ $purchaseOrder->supplier_contact ?? '—' }}</span></div>
        <div class="info-row"><span class="info-label">Termin Bayar</span><span>: {{ $purchaseOrder->payment_terms ?? '—' }}</span></div>
    </div>
    <div class="info-box">
        <h4>Informasi PO</h4>
        <div class="info-row"><span class="info-label">No. PO</span><span>: {{ $purchaseOrder->document_no }}</span></div>
        <div class="info-row"><span class="info-label">Tgl. Order</span><span>: {{ $purchaseOrder->order_date->format('d M Y') }}</span></div>
        <div class="info-row"><span class="info-label">Est. Terima</span><span>: {{ $purchaseOrder->expected_date?->format('d M Y') ?? '—' }}</span></div>
        <div class="info-row"><span class="info-label">Dari PR</span><span>: {{ $purchaseOrder->purchaseRequest->document_no ?? '—' }}</span></div>
        <div class="info-row"><span class="info-label">Dibuat Oleh</span><span>: {{ $purchaseOrder->creator?->name ?? '—' }}</span></div>
    </div>
</div>

@if($purchaseOrder->delivery_address)
<div class="notes-box">
    <h4>Alamat Pengiriman</h4>
    <div>{{ $purchaseOrder->delivery_address }}</div>
</div>
@endif

<table>
    <thead>
        <tr>
            <th class="text-center" style="width:40px;">No</th>
            <th>Nama Material</th>
            <th>Spesifikasi</th>
            <th>Satuan</th>
            <th class="text-right">Qty Order</th>
            <th class="text-right">Qty Diterima</th>
            <th class="text-right">Harga Satuan (Rp)</th>
            <th class="text-right">Total (Rp)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($purchaseOrder->items as $i => $item)
        <tr>
            <td class="text-center">{{ $i + 1 }}</td>
            <td>
                <strong>{{ $item->material_name }}</strong>
                @if($item->material_code)
                    <br><small style="color:#888; font-family:monospace;">{{ $item->material_code }}</small>
                @endif
            </td>
            <td>{{ $item->specification ?: '—' }}</td>
            <td class="text-center">{{ $item->unit }}</td>
            <td class="text-right">{{ number_format($item->quantity_ordered, 2) }}</td>
            <td class="text-right">{{ number_format($item->quantity_received, 2) }}</td>
            <td class="text-right">{{ $item->unit_price ? number_format($item->unit_price, 0, ',', '.') : '—' }}</td>
            <td class="text-right">{{ $item->total_price ? number_format($item->total_price, 0, ',', '.') : '—' }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="7" class="text-right" style="padding:10px 16px;">TOTAL:</td>
            <td class="text-right" style="padding:10px 16px; font-size:14px;">
                {{ number_format($purchaseOrder->items->sum('total_price'), 0, ',', '.') }}
            </td>
        </tr>
    </tfoot>
</table>

@if($purchaseOrder->notes)
<div class="notes-box">
    <h4>Catatan / Instruksi Khusus</h4>
    <div>{{ $purchaseOrder->notes }}</div>
</div>
@endif

<div class="footer">
    <div class="sign-box">
        <div class="sign-line"></div>
        <div class="sign-label">Dibuat Oleh</div>
        <div class="sign-name">{{ $purchaseOrder->creator?->name ?? '—' }}</div>
        <div style="font-size:11px; color:#888;">{{ $purchaseOrder->creator?->role_label ?? '' }}</div>
    </div>
    <div class="sign-box">
        <div class="sign-line"></div>
        <div class="sign-label">Kepala Gudang</div>
        <div class="sign-name">&nbsp;</div>
    </div>
    <div class="sign-box">
        <div class="sign-line"></div>
        <div class="sign-label">Disetujui / Pimpinan</div>
        <div class="sign-name">{{ $purchaseOrder->approver?->name ?? '—' }}</div>
        <div style="font-size:11px; color:#888;">{{ $purchaseOrder->approved_at?->format('d M Y') ?? '' }}</div>
    </div>
</div>

</body>
</html>