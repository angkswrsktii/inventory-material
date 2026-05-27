@extends('layouts.app')

@section('title', 'Detail Supplier')
@section('topbar-title', __('app.nav.master_data') . ' — ' . __('app.nav.data_supplier'))

@section('content')
<div class="breadcrumb">
    <a href="{{ route('suppliers.index') }}">{{ __('app.supplier.title') }}</a>
    <span class="sep">/</span>
    <span>{{ $supplier->name }}</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">{{ $supplier->name }}</div>
        <div class="page-subtitle">{{ __('app.supplier.detail_subtitle') }}</div>
    </div>
    <div style="display:flex; gap:10px;">
        @if(auth()->user()?->isAdmin())
        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-primary">
            <i class="fas fa-pen"></i> Edit
        </a>
        @endif
    </div>
</div>

<div style="display:grid; grid-template-columns: 350px 1fr; gap:20px; align-items:start;">
    <!-- Info Panel -->
    <div style="display:flex; flex-direction:column; gap:20px;">
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-building" style="color:var(--accent);margin-right:8px;"></i>{{ __('app.supplier.company_info') }}</span>
            </div>
            <div style="padding:0;">
                @php
                    $rows = [
                        [{{ __('app.supplier.code') }},   $supplier->code ?: '-'],
                        [{{ __('app.supplier.company_name') }}, $supplier->name],
                        [{{ __('app.supplier.contact_person') }},   $supplier->contact_person ?: '-'],
                        [{{ __('app.supplier.phone') }},         $supplier->phone ?: '-'],
                        ['Email',           $supplier->email ?: '-'],
                        [{{ __('app.common.status') }},          $supplier->is_active ? {{ __('app.common.active') }} : {{ __('app.common.inactive') }}],
                    ];
                @endphp
                @foreach($rows as [$label, $value])
                <div style="display:flex; justify-content:space-between; align-items:center;
                            padding:11px 20px; border-bottom:1px solid var(--border); gap:12px;">
                    <span style="font-size:12px; color:var(--text-muted); white-space:nowrap;">{{ $label }}</span>
                    <span style="font-size:13px; color:var(--text); font-weight:500; text-align:right;">{{ $value }}</span>
                </div>
                @endforeach
                <div style="padding:12px 20px;">
                    <div style="font-size:12px; color:var(--text-muted); margin-bottom:5px;">{{ __('app.supplier.address') }}</div>
                    <div style="font-size:13px; color:var(--text);">{{ $supplier->address ?: '-' }}</div>
                </div>
            </div>
        </div>

        </div>
    </div>

    <!-- PO History -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-file-invoice" style="color:var(--accent-2);margin-right:8px;"></i>{{ __('app.supplier.po_history') }}</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>{{ __('app.supplier.po_date') }}</th>
                        <th>{{ __('app.common.po_number') }}</th>
                        <th>{{ __('app.common.total_item') }}</th>
                        <th>{{ __('app.common.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($supplier->purchaseOrders as $po)
                    <tr>
                        <td style="color:var(--text-muted);">{{ $po->po_date->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('purchase-orders.show', $po) }}" class="mono" style="color:var(--text); text-decoration:none; font-weight:600;">
                                {{ $po->po_number }}
                            </a>
                        </td>
                        <td>{{ $po->items->count() }} item</td>
                        <td>
                            <span class="badge" style="background:var(--surface-2); color:var(--text);">{{ ucfirst($po->status) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="empty-state" style="padding:40px;">
                                <i class="fas fa-inbox"></i>
                                <h4>{{ __('app.supplier.no_po') }}</h4>
                                <p>{{ __('app.supplier.no_po_desc') }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection