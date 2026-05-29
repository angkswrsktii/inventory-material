@extends('layouts.app')
@section('title', __('app.good_receipt.title'))
@section('topbar-title', __('app.nav.good_receipt') . ' — ' . __('app.nav.good_receipt'))

@section('content')
<div class="breadcrumb">
    <a href="{{ route('good-receipts.index') }}">{{ __('app.good_receipt.title') }}</a>
    <span class="sep">/</span>
    <span>{{ $goodReceipt->gr_number }}</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">{{ $goodReceipt->gr_number }}</div>
        <div class="page-subtitle">{{ __('app.good_receipt.detail_subtitle') }}</div>
    </div>
    <div style="display:flex; gap:10px;">
        <a href="{{ route('reports.print.good-receipt', $goodReceipt) }}" target="_blank" class="btn btn-secondary">
            <i class="fas fa-print"></i> {{ __('app.btn.print') }}
        </a>
    </div>
</div>

<div style="display:grid; grid-template-columns: 320px 1fr; gap:20px; align-items:start;">
    <!-- Info Panel -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-info-circle" style="color:var(--accent);margin-right:8px;"></i>{{ __('app.good_receipt.info_title') }}</span>
        </div>
        <div style="padding:0;">
            @php
                $rows = [
                    [__('app.good_receipt.no_gr'),           $goodReceipt->gr_number],
                    [__('app.good_receipt.no_po'),           $goodReceipt->purchaseOrder->po_number ?? '-'],
                    [__('app.good_receipt.receive_date'),    $goodReceipt->receipt_date->format('d M Y')],
                    [__('app.common.supplier'),              $goodReceipt->purchaseOrder->supplier->name ?? '-'],
                    [__('app.good_receipt.system_receiver'), $goodReceipt->receiver->name ?? '-'],
                    [__('app.good_receipt.pic_receiver'),    $goodReceipt->pic->name ?? '-'],
                    [__('app.common.project'),               $goodReceipt->project->name ?? '-'],
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
                <div style="font-size:12px; color:var(--text-muted); margin-bottom:5px;">{{ __('app.common.notes') }}</div>
                <div style="font-size:13px; color:var(--text);">{{ $goodReceipt->notes }}</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Items -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-boxes-stacked" style="color:var(--accent-2);margin-right:8px;"></i>{{ __('app.good_receipt.items_title') }}</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th width="50">{{ __('app.common.no') }}</th>
                        <th>{{ __('app.common.item_name') }}</th>
                        <th>{{ __('app.good_receipt.col_load_number') }}</th>
                        <th class="text-right">{{ __('app.good_receipt.col_po_qty') }}</th>
                        <th class="text-right">{{ __('app.good_receipt.col_received_qty') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($goodReceipt->items as $item)
                    <tr>
                        <td style="color:var(--text-muted);">{{ $loop->iteration }}</td>
                        <td>
                            <div style="font-weight:500;">{{ $item->material->name ?? '-' }}</div>
                            <div style="font-size:11px; color:var(--text-muted);">{{ $item->material->code ?? '-' }}</div>
                        </td>
                        <td>
                            @if($item->load_material_number)
                                <span style="font-family:monospace; font-size:12px; background:var(--surface-2); padding:3px 8px; border-radius:4px; color:var(--accent);">
                                    {{ $item->load_material_number }}
                                </span>
                            @else
                                <span style="color:var(--text-muted); font-size:12px;">-</span>
                            @endif
                        </td>
                        <td class="text-right" style="color:var(--text-muted);">
                            {{ number_format($item->purchaseOrderItem->quantity ?? 0, 2) }} {{ $item->unit ?? 'Pcs' }}
                        </td>
                        <td class="text-right" style="font-weight:600; color:var(--success);">
                            {{ number_format($item->quantity, 2) }} {{ $item->unit ?? 'Pcs' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
