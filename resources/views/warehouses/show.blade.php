@extends('layouts.app')
@section('title', 'Detail Gudang')
@section('topbar-title', __('app.nav.master_data') . ' — ' . __('app.nav.data_warehouse'))

@section('content')
<div class="breadcrumb">
    <a href="{{ route('warehouses.index') }}">{{ __('app.nav.data_warehouse') }}</a>
    <span class="sep">/</span>
    <span>{{ $warehouse->name }}</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">{{ $warehouse->name }}</div>
        <div class="page-subtitle">Detail informasi gudang</div>
    </div>
    <div style="display:flex; gap:8px;">
        <a href="{{ route('warehouses.edit', $warehouse) }}" class="btn btn-primary btn-sm">
            <i class="fas fa-pen"></i> Edit
        </a>
        <a href="{{ route('warehouses.index') }}" class="btn btn-ghost btn-sm">
            <i class="fas fa-arrow-left"></i> {{ __("app.btn.back") }}
        </a>
    </div>
</div>

@if(session('success'))
    <div style="background:var(--success-bg); border:1px solid var(--success); color:var(--success); padding:12px 16px; border-radius:var(--radius-sm); margin-bottom:16px; display:flex; align-items:center; gap:8px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<div style="display:grid; grid-template-columns:1fr 2fr; gap:20px; align-items:start;">

    <!-- Info Card -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-warehouse" style="color:var(--accent-2);margin-right:8px;"></i>{{ __("app.warehouse.info") }}</span>
        </div>
        <div class="card-body" style="padding:0;">
            <table style="width:100%; border-collapse:collapse;">
                <tr style="border-bottom:1px solid var(--border);">
                    <td style="padding:12px 16px; color:var(--text-muted); font-size:12px; width:40%; white-space:nowrap;">{{ __('app.common.code') }}</td>
                    <td style="padding:12px 16px; font-weight:600; font-family:monospace;">{{ $warehouse->code }}</td>
                </tr>
                <tr style="border-bottom:1px solid var(--border);">
                    <td style="padding:12px 16px; color:var(--text-muted); font-size:12px;">Nama</td>
                    <td style="padding:12px 16px; font-weight:600;">{{ $warehouse->name }}</td>
                </tr>
                <tr style="border-bottom:1px solid var(--border);">
                    <td style="padding:12px 16px; color:var(--text-muted); font-size:12px;">{{ __('app.warehouse.location') }}</td>
                    <td style="padding:12px 16px; color:var(--text-muted);">{{ $warehouse->location ?: '—' }}</td>
                </tr>
                <tr style="border-bottom:1px solid var(--border);">
                    <td style="padding:12px 16px; color:var(--text-muted); font-size:12px;">{{ __('app.common.status') }}</td>
                    <td style="padding:12px 16px;">
                        @if($warehouse->is_active)
                            <span class="badge badge-success">{{ __('app.common.active') }}</span>
                        @else
                            <span class="badge badge-danger">{{ __('app.common.inactive') }}</span>
                        @endif
                    </td>
                </tr>
                <tr style="border-bottom:1px solid var(--border);">
                    <td style="padding:12px 16px; color:var(--text-muted); font-size:12px;">Jumlah Stok</td>
                    <td style="padding:12px 16px; font-weight:600;">{{ $warehouse->stocks->count() }} item</td>
                </tr>
                <tr>
                    <td style="padding:12px 16px; color:var(--text-muted); font-size:12px;">Dibuat</td>
                    <td style="padding:12px 16px; color:var(--text-muted); font-size:12px;">{{ $warehouse->created_at->format('d M Y') }}</td>
                </tr>
            </table>
        </div>
        <div class="card-body" style="border-top:1px solid var(--border);">
            <form action="{{ route('warehouses.toggle-active', $warehouse) }}" method="POST">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-ghost btn-sm" style="color:{{ $warehouse->is_active ? 'var(--warning)' : 'var(--success)' }}; width:100%;">
                    <i class="fas fa-{{ $warehouse->is_active ? 'ban' : 'check' }}"></i>
                    {{ $warehouse->is_active ? '{{ __("app.warehouse.deactivate") }}' : '{{ __("app.warehouse.activate") }}' }}
                </button>
            </form>
        </div>
    </div>

    <!-- Stocks Table -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-boxes" style="color:var(--accent-2);margin-right:8px;"></i>Stok di Gudang Ini</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th width="50">{{ __('app.common.no') }}</th>
                        <th>Material</th>
                        <th width="100">{{ __('app.common.stock') }}</th>
                        <th width="80">Satuan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($warehouse->stocks as $i => $stock)
                    <tr>
                        <td style="color:var(--text-muted);">{{ $i + 1 }}</td>
                        <td>
                            @if($stock->material)
                                <div style="font-weight:500;">{{ $stock->material->name ?? '—' }}</div>
                                <div style="font-size:11px; color:var(--text-muted);">{{ $stock->material->code ?? '' }}</div>
                            @else
                                <span style="color:var(--text-muted);">—</span>
                            @endif
                        </td>
                        <td style="font-weight:600;">{{ number_format($stock->quantity ?? 0) }}</td>
                        <td style="color:var(--text-muted); font-size:12px;">{{ $stock->unit ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="empty-state" style="padding:40px 20px;">
                                <i class="fas fa-box-open"></i>
                                <h4>{{ __("app.stock.empty_title") }}</h4>
                                <p>{{ __("app.warehouse.no_stock") }}</p>
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
