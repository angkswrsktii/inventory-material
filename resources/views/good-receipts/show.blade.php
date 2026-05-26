@extends('layouts.app')

@section('title', 'Detail Good Receipt')
@section('topbar-title', 'Detail Good Receipt')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('good-receipts.index') }}">Good Receipt</a>
    <span class="sep">/</span>
    <span>{{ $goodReceipt->gr_number }}</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">{{ $goodReceipt->gr_number }}</div>
        <div class="page-subtitle">Detail penerimaan Material</div>
    </div>
    <div style="display:flex; gap:10px;">
        <a href="{{ route('reports.print.good-receipt', $goodReceipt) }}" target="_blank" class="btn btn-secondary">
            <i class="fas fa-print"></i> Print GR
        </a>
    </div>
</div>

<div style="display:grid; grid-template-columns: 320px 1fr; gap:20px; align-items:start;">
    <!-- Info Panel -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-info-circle" style="color:var(--accent);margin-right:8px;"></i>Informasi Dokumen</span>
        </div>
        <div style="padding:0;">
            @php
                $rows = [
                    ['No. GR',          $goodReceipt->gr_number],
                    ['No. PO',          $goodReceipt->purchaseOrder->po_number ?? '-'],
                    ['Tanggal Terima',  $goodReceipt->receipt_date->format('d M Y')],
                    ['Supplier',        $goodReceipt->purchaseOrder->supplier->name ?? '-'],
                    ['Penerima (System)', $goodReceipt->receiver->name ?? '-'],
                    ['PIC Penerima',    $goodReceipt->pic->name ?? '-'],
                    ['Project',         $goodReceipt->project->name ?? '-'],
                ];
            @endphp
            @foreach($rows as [$label, $value])
            <div style="display:flex; justify-content:space-between; align-items:center;
                        padding:11px 20px; border-bottom:1px solid var(--border); gap:12px;">
                <span style="font-size:12px; color:var(--text-muted); white-space:nowrap;">{{ $label }}</span>
                <span style="font-size:13px; color:var(--text); font-weight:500; text-align:right;">{{ $value }}</span>
            </div>
            @endforeach
            @if($goodReceipt->notes)
            <div style="padding:12px 20px;">
                <div style="font-size:12px; color:var(--text-muted); margin-bottom:5px;">Catatan</div>
                <div style="font-size:13px; color:var(--text);">{{ $goodReceipt->notes }}</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Items -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-boxes-stacked" style="color:var(--accent-2);margin-right:8px;"></i>Item Diterima</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Material</th>
                        <th class="text-right">Qty PO</th>
                        <th class="text-right">Qty Terima</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($goodReceipt->items as $item)
                    <tr>
                        <td style="color:var(--text-muted);">{{ $loop->iteration }}</td>
                        <td>
                            <div style="font-weight:500;">{{ $item->material->name ?? '-' }}</div>
                            <div style="font-size:11px; color:var(--text-muted);">
                                {{ $item->material->code ?? '-' }}
                            </div>
                        </td>
                        <td class="text-right" style="color:var(--text-muted);">
                            {{ number_format($item->purchaseOrderItem->quantity ?? 0, 2) }} 
                            {{ $item->unit ?? 'Pcs' }}
                        </td>
                        <td class="text-right" style="font-weight:600; color:var(--success);">
                            {{ number_format($item->quantity, 2) }} 
                            {{ $item->unit ?? 'Pcs' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
