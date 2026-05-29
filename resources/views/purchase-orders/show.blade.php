@extends('layouts.app')

@section('title', 'Detail Purchase Order')
@section('topbar-title', __('app.nav.purchasing') . ' — ' . __('app.nav.purchase_order'))

@section('content')
<div class="breadcrumb">
    <a href="{{ route('purchase-orders.index') }}">Purchase Order</a>
    <span class="sep">/</span>
    <span>{{ $purchaseOrder->po_number }}</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">{{ $purchaseOrder->po_number }}</div>
        <div class="page-subtitle">Detail pesanan pembelian material/part ke supplier</div>
    </div>
    <div style="display:flex; gap:10px;">
        <a href="{{ route('purchase-orders.print', $purchaseOrder) }}" target="_blank" class="btn btn-secondary">
            <i class="fas fa-print"></i> Print PO
        </a>
       @if($purchaseOrder->status === 'draft')
            <form action="{{ route('purchase-orders.send', $purchaseOrder) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-primary"
                        data-confirm-btn="Kirim dan tandai PO ini sebagai Issued?"
                        data-confirm-ok="Ya, Kirim"
                        data-confirm-class="btn-primary"
                        data-confirm-icon="fa-paper-plane"
                        data-confirm-iconbg="rgba(79,142,247,0.12)"
                        data-confirm-iconc="var(--accent)"><i class="fas fa-paper-plane"></i> Proses / Issued PO</button>
            </form>
            <a href="{{ route('purchase-orders.edit', $purchaseOrder) }}" class="btn btn-secondary"><i class="fas fa-pen"></i> Edit</a>
        @endif
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
                    ['No. PO',          $purchaseOrder->po_number],
                    ['Tanggal PO',      $purchaseOrder->order_date->format('d M Y')],
                    ['Supplier',        $purchaseOrder->supplier->name ?? '-'],
                    ['No. PR Ref',      $purchaseOrder->purchaseRequest->pr_number ?? '-'],
                ];
            @endphp
            @foreach($rows as [$label, $value])
            <div style="display:flex; justify-content:space-between; align-items:center; padding:11px 20px; border-bottom:1px solid var(--border); gap:12px;">
                <span style="font-size:12px; color:var(--text-muted); white-space:nowrap;">{{ $label }}</span>
                <span style="font-size:13px; color:var(--text); font-weight:500; text-align:right;">
                    @if($label === 'No. PR Ref' && $purchaseOrder->purchaseRequest)
                        <a href="{{ route('purchase-requests.show', $purchaseOrder->purchaseRequest) }}" class="mono" style="color:var(--accent); text-decoration:none;">{{ $value }}</a>
                    @else
                        {{ $value }}
                    @endif
                </span>
            </div>
            @endforeach
            <div style="padding:14px 20px; background:var(--surface-2);">
                <div style="font-size:12px; color:var(--text-muted); margin-bottom:5px;">Status PO</div>
                @if($purchaseOrder->status === 'draft')
                    <span class="badge badge-secondary" style="background:#e2e8f0; color:#475569;">Draft</span>
                @elseif($purchaseOrder->status === 'issued')
                    <span class="badge badge-primary">Issued</span>
                @elseif($purchaseOrder->status === 'partial')
                    <span class="badge badge-info">Partial</span>
                @elseif($purchaseOrder->status === 'completed')
                    <span class="badge badge-success">Completed</span>
                @elseif($purchaseOrder->status === 'cancelled')
                    <span class="badge badge-danger">Cancelled</span>
                @endif
            </div>
            @if($purchaseOrder->notes)
            <div style="padding:12px 20px;">
                <div style="font-size:12px; color:var(--text-muted); margin-bottom:5px;">{{ __("app.common.additional_notes") }}</div>
                <div style="font-size:13px; color:var(--text);">{{ $purchaseOrder->notes }}</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Items -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-cubes" style="color:var(--accent-2);margin-right:8px;"></i>Item Pesanan</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th width="40">#</th>
                        <th>{{ __('app.common.code') }}</th>
                        <th>Material</th>
                        <th class="text-right">{{ __('app.common.qty') }}</th>
                        <th class="text-right">Harga Satuan</th>
                        <th class="text-right">Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @php $grandTotal = 0; @endphp
                    @foreach($purchaseOrder->items as $item)
                    @php $grandTotal += $item->price; @endphp
                    <tr>
                        <td style="color:var(--text-muted);">{{ $loop->iteration }}</td>
                        <td><span class="mono" style="font-size:11px;">{{ $item->material->code ?? '-' }}</span></td>
                        <td style="font-weight:500;">{{ $item->material->name ?? '-' }}</td>
                        <td class="text-right" style="font-weight:600; color:var(--success);">
                            {{ number_format($item->quantity, 2) }} {{ $item->unit ?? 'Pcs' }}
                        </td>
                        <td class="text-right">Rp {{ number_format($item->price_per_qty, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-right" style="font-weight:700; padding:12px;">GRAND TOTAL</td>
                        <td class="text-right" style="font-weight:700; color:var(--accent); font-size:14px; padding:12px;">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection