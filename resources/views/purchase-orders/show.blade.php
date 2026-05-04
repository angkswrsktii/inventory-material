@extends('layouts.app')
@section('title', 'Detail Purchase Order')
@section('topbar-title', 'Purchase Order')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('purchase-orders.index') }}">Purchase Order</a>
    <span class="sep">/</span>
    <span>{{ $purchaseOrder->document_no }}</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">{{ $purchaseOrder->document_no }}</div>
        <div class="page-subtitle">
            {{ $purchaseOrder->supplier_name }} &nbsp;·&nbsp;
            {{ $purchaseOrder->order_date->format('d F Y') }}
        </div>
    </div>
    <div style="display:flex; gap:10px; flex-wrap:wrap;">
        <a href="{{ route('purchase-orders.print', $purchaseOrder) }}" target="_blank" class="btn btn-ghost">
            <i class="fas fa-print"></i> Print PO
        </a>
        @if($purchaseOrder->canSend() && (auth()->user()->isPimpinan() || auth()->user()->isAdmin()))
        <form action="{{ route('purchase-orders.send', $purchaseOrder) }}" method="POST"
              onsubmit="return confirm('Konfirmasi kirim PO ini ke supplier?')">
            @csrf
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i> Kirim ke Supplier
            </button>
        </form>
        @endif
        @if($purchaseOrder->canCancel() && (auth()->user()->isPimpinan() || auth()->user()->isAdmin()))
        <button type="button" class="btn btn-danger" onclick="document.getElementById('cancelModal').style.display='flex'">
            <i class="fas fa-ban"></i> Batalkan PO
        </button>
        @endif
        @if(in_array($purchaseOrder->status, ['sent', 'partial']))
        <button type="button" class="btn btn-success"
        onclick="document.getElementById('receiveModal').style.display='flex'">
            <i class="fas fa-boxes-stacked"></i> Catat Penerimaan
        </button>
        @endif
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 300px; gap:20px; align-items:start;">

    {{-- Items --}}
    <div style="display:flex; flex-direction:column; gap:20px;">
        <div class="card">
            <div class="card-header">
                <span class="card-title">
                    <i class="fas fa-boxes-stacked" style="color:var(--accent);margin-right:8px;"></i>
                    Item Material Dipesan
                </span>
                <span class="badge badge-info">{{ $purchaseOrder->items->count() }} item</span>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Material</th>
                            <th>Spesifikasi</th>
                            <th>Satuan</th>
                            <th class="text-right">Qty Order</th>
                            <th class="text-right">Qty Diterima</th>
                            <th class="text-right">Sisa</th>
                            <th class="text-right">Harga Satuan</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchaseOrder->items as $i => $item)
                        <tr>
                            <td style="color:var(--text-dim);">{{ $i + 1 }}</td>
                            <td>
                                <div style="font-weight:500;">{{ $item->material_name }}</div>
                                @if($item->material_code)
                                    <div style="font-size:11px; color:var(--text-muted); font-family:monospace;">{{ $item->material_code }}</div>
                                @endif
                            </td>
                            <td style="font-size:12px; color:var(--text-muted);">{{ $item->specification ?: '—' }}</td>
                            <td><span class="badge badge-muted">{{ $item->unit }}</span></td>
                            <td class="text-right" style="font-weight:600;">{{ number_format($item->quantity_ordered, 2) }}</td>
                            <td class="text-right" style="color:var(--success); font-weight:600;">
                                {{ number_format($item->quantity_received, 2) }}
                            </td>
                            <td class="text-right" style="color:{{ $item->remaining_qty > 0 ? 'var(--warning)' : 'var(--success)' }}; font-weight:600;">
                                {{ number_format($item->remaining_qty, 2) }}
                            </td>
                            <td class="text-right" style="font-size:12.5px;">
                                @if($item->unit_price)
                                    Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                @else
                                    <span style="color:var(--text-dim);">—</span>
                                @endif
                            </td>
                            <td class="text-right" style="font-weight:600;">
                                @if($item->unit_price)
                                    Rp {{ number_format($item->total_price_computed, 0, ',', '.') }}
                                @else
                                    <span style="color:var(--text-dim);">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background:var(--surface-2);">
                            <td colspan="8" style="padding:12px 16px; text-align:right; font-weight:600; color:var(--text-muted);">TOTAL:</td>
                            <td class="text-right" style="padding:12px 16px; font-weight:700; color:var(--accent); font-size:15px;">
                                Rp {{ number_format($purchaseOrder->items->sum('total_price'), 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Link ke PR --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">
                    <i class="fas fa-link" style="color:var(--accent-2);margin-right:8px;"></i>
                    Purchase Request Terkait
                </span>
            </div>
            <div style="padding:16px 20px; display:flex; align-items:center; gap:14px;">
                <div style="width:40px;height:40px;border-radius:10px;background:var(--accent-glow);display:flex;align-items:center;justify-content:center;color:var(--accent);">
                    <i class="fas fa-file-pen"></i>
                </div>
                <div style="flex:1;">
                    <div style="font-weight:600; font-family:monospace; color:var(--accent);">
                        {{ $purchaseOrder->purchaseRequest->document_no ?? '—' }}
                    </div>
                    <div style="font-size:12px; color:var(--text-muted);">
                        Oleh: {{ $purchaseOrder->purchaseRequest->requested_by_name ?? '—' }}
                        &nbsp;·&nbsp;
                        {{ $purchaseOrder->purchaseRequest->request_date?->format('d M Y') ?? '—' }}
                    </div>
                </div>
                <a href="{{ route('purchase-requests.show', $purchaseOrder->purchaseRequest) }}" class="btn btn-ghost btn-sm">
                    <i class="fas fa-eye"></i> Lihat PR
                </a>
            </div>
        </div>
    </div>

    {{-- Info Panel --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">
                <i class="fas fa-file-invoice" style="color:var(--accent-2);margin-right:8px;"></i>
                Detail PO
            </span>
            <span class="badge badge-{{ $purchaseOrder->status_color }}">{{ $purchaseOrder->status_label }}</span>
        </div>
        <div>
            @php
                $rows = [
                    ['No. PO',            $purchaseOrder->document_no],
                    ['Tgl. Order',        $purchaseOrder->order_date->format('d M Y')],
                    ['Est. Terima',       $purchaseOrder->expected_date?->format('d M Y') ?? '—'],
                    ['Supplier',          $purchaseOrder->supplier_name],
                    ['Kontak',            $purchaseOrder->supplier_contact ?? '—'],
                    ['Termin Bayar',      $purchaseOrder->payment_terms ?? '—'],
                    ['Dibuat Oleh',       $purchaseOrder->creator?->name ?? '—'],
                    ['Dikonfirmasi',      $purchaseOrder->approver?->name ?? '—'],
                    ['Tgl. Konfirmasi',   $purchaseOrder->approved_at?->format('d M Y H:i') ?? '—'],
                ];
            @endphp
            @foreach($rows as [$label, $value])
            <div style="display:flex; justify-content:space-between; padding:10px 18px;
                        border-bottom:1px solid var(--border); gap:10px; align-items:center;">
                <span style="font-size:12px; color:var(--text-muted); white-space:nowrap;">{{ $label }}</span>
                <span style="font-size:13px; color:var(--text); font-weight:500; text-align:right;">{{ $value }}</span>
            </div>
            @endforeach
            @if($purchaseOrder->delivery_address)
            <div style="padding:12px 18px; border-bottom:1px solid var(--border);">
                <div style="font-size:12px; color:var(--text-muted); margin-bottom:4px;">Alamat Pengiriman</div>
                <div style="font-size:12.5px; color:var(--text);">{{ $purchaseOrder->delivery_address }}</div>
            </div>
            @endif
            @if($purchaseOrder->notes)
            <div style="padding:12px 18px;">
                <div style="font-size:12px; color:var(--text-muted); margin-bottom:4px;">Catatan</div>
                <div style="font-size:12.5px; color:var(--text);">{{ $purchaseOrder->notes }}</div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Cancel Modal --}}
<div id="cancelModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6);
     z-index:999; align-items:center; justify-content:center;">
    <div style="background:var(--surface); border:1px solid var(--border); border-radius:var(--radius);
                padding:28px; width:420px; max-width:90vw;">
        <div style="font-family:'Syne',sans-serif; font-weight:700; font-size:16px; margin-bottom:6px;">
            Batalkan Purchase Order
        </div>
        <div style="font-size:13px; color:var(--text-muted); margin-bottom:20px;">
            Status PR akan dikembalikan ke "Disetujui" agar bisa dibuat PO baru.
        </div>
        <form action="{{ route('purchase-orders.cancel', $purchaseOrder) }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Alasan Pembatalan <span class="required">*</span></label>
                <textarea name="cancel_reason" class="form-control" rows="3"
                          placeholder="Jelaskan alasan pembatalan..." required></textarea>
            </div>
            <div style="display:flex; gap:10px; margin-top:16px;">
                <button type="submit" class="btn btn-danger"><i class="fas fa-ban"></i> Batalkan PO</button>
                <button type="button" class="btn btn-ghost"
                        onclick="document.getElementById('cancelModal').style.display='none'">
                    Tutup
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Receive Modal --}}
<div id="receiveModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6);
     z-index:999; align-items:center; justify-content:center;">
    <div style="background:var(--surface); border:1px solid var(--border); border-radius:var(--radius);
                padding:28px; width:560px; max-width:90vw; max-height:90vh; overflow-y:auto;">
        <div style="font-family:'Syne',sans-serif; font-weight:700; font-size:16px; margin-bottom:6px;">
            Catat Penerimaan Barang
        </div>
        <div style="font-size:13px; color:var(--text-muted); margin-bottom:20px;">
            Isi jumlah yang diterima sekarang untuk masing-masing item.
        </div>
        <form action="{{ route('purchase-orders.receive', $purchaseOrder) }}" method="POST">
            @csrf
            <table style="width:100%; font-size:13px; border-collapse:collapse;">
                <thead>
                    <tr style="border-bottom:1px solid var(--border);">
                        <th style="padding:8px; text-align:left;">Material</th>
                        <th style="padding:8px; text-align:right;">Sisa</th>
                        <th style="padding:8px; text-align:right; width:120px;">Terima Sekarang</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchaseOrder->items as $item)
                    <tr style="border-bottom:1px solid var(--border);">
                        <td style="padding:8px;">{{ $item->material_name }}</td>
                        <td style="padding:8px; text-align:right; color:var(--warning);">
                            {{ number_format($item->remaining_qty, 2) }} {{ $item->unit }}
                        </td>
                        <td style="padding:8px;">
                            <input type="number" name="quantities[{{ $item->id }}]"
                                   class="form-control" min="0"
                                   max="{{ $item->remaining_qty }}"
                                   step="0.01" value="0"
                                   {{ $item->remaining_qty <= 0 ? 'disabled' : '' }}>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="display:flex; gap:10px; margin-top:20px;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check"></i> Simpan Penerimaan
                </button>
                <button type="button" class="btn btn-ghost"
                        onclick="document.getElementById('receiveModal').style.display='none'">
                    Tutup
                </button>
            </div>
        </form>
    </div>
</div>

@endsection