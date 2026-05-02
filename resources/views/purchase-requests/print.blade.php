<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print PR — {{ $purchaseRequest->document_no }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Arial', sans-serif; font-size: 12px; color: #222; background: #fff; padding: 24px; }

        .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #222; padding-bottom: 12px; margin-bottom: 16px; }
        .company-name { font-size: 18px; font-weight: 700; }
        .doc-title { text-align: right; }
        .doc-title h2 { font-size: 15px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
        .doc-no { font-size: 13px; color: #444; margin-top: 4px; font-family: monospace; }

        .meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0; border: 1px solid #ccc; margin-bottom: 16px; }
        .meta-row { display: contents; }
        .meta-label { padding: 6px 10px; font-weight: 600; background: #f5f5f5; border-bottom: 1px solid #ccc; border-right: 1px solid #ccc; }
        .meta-value { padding: 6px 10px; border-bottom: 1px solid #ccc; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th { background: #f0f0f0; padding: 7px 10px; text-align: left; border: 1px solid #ccc; font-size: 11px; text-transform: uppercase; letter-spacing: .3px; }
        td { padding: 6px 10px; border: 1px solid #ccc; vertical-align: top; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        tfoot td { background: #f5f5f5; font-weight: 600; }

        .status-badge { display: inline-block; padding: 2px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .status-draft     { background:#eee; color:#555; }
        .status-submitted { background:#fef3c7; color:#92400e; }
        .status-approved  { background:#d1fae5; color:#065f46; }
        .status-rejected  { background:#fee2e2; color:#991b1b; }
        .status-ordered   { background:#dbeafe; color:#1e40af; }

        .signatures { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0; border: 1px solid #ccc; margin-top: 24px; }
        .sig-box { padding: 12px; border-right: 1px solid #ccc; text-align: center; }
        .sig-box:last-child { border-right: none; }
        .sig-label { font-size: 11px; font-weight: 600; text-transform: uppercase; margin-bottom: 50px; }
        .sig-name { border-top: 1px solid #888; padding-top: 6px; font-size: 11px; color: #555; }

        .print-footer { margin-top: 20px; font-size: 10px; color: #999; text-align: center; border-top: 1px solid #eee; padding-top: 10px; }

        @media print {
            body { padding: 10px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<div class="no-print" style="margin-bottom:16px;">
    <button onclick="window.print()" style="padding:8px 20px;background:#1a56db;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:13px;">
        🖨️ Print / Save PDF
    </button>
    <a href="{{ route('purchase-requests.show', $purchaseRequest) }}" style="margin-left:10px;font-size:13px;color:#555;">← Kembali</a>
</div>

<div class="header">
    <div>
        <div class="company-name">Material Inventory System</div>
        <div style="font-size:11px;color:#666;margin-top:2px;">Dokumen Resmi — Jangan didistribusikan tanpa izin</div>
    </div>
    <div class="doc-title">
        <h2>Purchase Request</h2>
        <div class="doc-no">{{ $purchaseRequest->document_no }}</div>
        <div style="margin-top:6px;">
            <span class="status-badge status-{{ $purchaseRequest->status }}">
                {{ $purchaseRequest->status_label }}
            </span>
        </div>
    </div>
</div>

<div class="meta-grid">
    <div class="meta-label">Tanggal Request</div>
    <div class="meta-value">{{ $purchaseRequest->request_date->format('d F Y') }}</div>
    <div class="meta-label">Nama Pemohon</div>
    <div class="meta-value">{{ $purchaseRequest->requested_by_name }}</div>
    <div class="meta-label">Departemen</div>
    <div class="meta-value">{{ $purchaseRequest->department ?: '—' }}</div>
    <div class="meta-label">Keperluan</div>
    <div class="meta-value">{{ $purchaseRequest->purpose ?: '—' }}</div>
    @if($purchaseRequest->notes)
    <div class="meta-label">Catatan</div>
    <div class="meta-value">{{ $purchaseRequest->notes }}</div>
    @endif
    @if($purchaseRequest->reviewer)
    <div class="meta-label">Disetujui oleh</div>
    <div class="meta-value">{{ $purchaseRequest->reviewer->name }} — {{ $purchaseRequest->reviewed_at?->format('d M Y, H:i') }}</div>
    @endif
</div>

<table>
    <thead>
        <tr>
            <th style="width:30px;">#</th>
            <th>Nama Material / Barang</th>
            <th>Kode</th>
            <th>Spesifikasi</th>
            <th class="text-center">Satuan</th>
            <th class="text-right">Qty Diminta</th>
            @if(in_array($purchaseRequest->status, ['approved','ordered']))
            <th class="text-right">Qty Disetujui</th>
            @endif
            <th class="text-right">Harga Est.</th>
            <th class="text-right">Subtotal</th>
            <th>Catatan</th>
        </tr>
    </thead>
    <tbody>
        @php $grandTotal = 0; @endphp
        @foreach($purchaseRequest->items as $i => $item)
        @php $grandTotal += $item->subtotal; @endphp
        <tr>
            <td class="text-center">{{ $i+1 }}</td>
            <td>{{ $item->material_name }}</td>
            <td style="font-family:monospace;font-size:11px;">{{ $item->material_code ?: '—' }}</td>
            <td style="font-size:11px;">{{ $item->specification ?: '—' }}</td>
            <td class="text-center">{{ $item->unit }}</td>
            <td class="text-right">{{ number_format($item->quantity_requested, 2) }}</td>
            @if(in_array($purchaseRequest->status, ['approved','ordered']))
            <td class="text-right">{{ $item->quantity_approved !== null ? number_format($item->quantity_approved, 2) : '—' }}</td>
            @endif
            <td class="text-right">{{ $item->estimated_price ? 'Rp '.number_format($item->estimated_price, 0, ',', '.') : '—' }}</td>
            <td class="text-right">{{ $item->estimated_price ? 'Rp '.number_format($item->subtotal, 0, ',', '.') : '—' }}</td>
            <td style="font-size:11px;">{{ $item->item_notes ?: '—' }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="{{ in_array($purchaseRequest->status, ['approved','ordered']) ? 7 : 6 }}" class="text-right">Total Estimasi</td>
            <td colspan="2" class="text-right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
            <td></td>
        </tr>
    </tfoot>
</table>

@if($purchaseRequest->status === 'rejected' && $purchaseRequest->rejection_reason)
<div style="border:1px solid #fca5a5;background:#fef2f2;padding:10px 14px;border-radius:4px;margin-bottom:16px;">
    <strong style="color:#991b1b;">Alasan Penolakan:</strong>
    <span style="color:#7f1d1d;"> {{ $purchaseRequest->rejection_reason }}</span>
</div>
@endif

<div class="signatures">
    <div class="sig-box">
        <div class="sig-label">Dibuat oleh</div>
        <div class="sig-name">{{ $purchaseRequest->creator?->name ?? $purchaseRequest->requested_by_name }}</div>
    </div>
    <div class="sig-box">
        <div class="sig-label">Kepala Gudang</div>
        <div class="sig-name">
            @if($purchaseRequest->reviewer && $purchaseRequest->reviewer->isKepalaGudang())
                {{ $purchaseRequest->reviewer->name }}
            @else
                ___________________
            @endif
        </div>
    </div>
    <div class="sig-box">
        <div class="sig-label">Pimpinan / Manager</div>
        <div class="sig-name">
            @if($purchaseRequest->reviewer && $purchaseRequest->reviewer->isManagement())
                {{ $purchaseRequest->reviewer->name }}
            @else
                ___________________
            @endif
        </div>
    </div>
</div>

<div class="print-footer">
    Dicetak pada {{ now()->format('d F Y, H:i') }} — {{ config('app.name', 'Material App') }}
</div>

</body>
</html>