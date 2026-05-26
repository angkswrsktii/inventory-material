@extends('layouts.app')

@section('title', 'Detail Mutasi Stok')
@section('topbar-title', __('app.nav.inventory') . ' — ' . __('app.nav.inventory_stock'))

@section('content')
<div class="breadcrumb">
    <a href="{{ route('inventory-stocks.index') }}">Inventory Stock</a>
    <span class="sep">/</span>
    <span>{{ $stock->material->name ?? $stock->part->part_name ?? '-' }} di {{ $stock->warehouse->name ?? '-' }}</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">Mutasi Stok</div>
        <div class="page-subtitle">
            <span class="mono" style="color:var(--accent);">{{ $stock->material->code ?? $stock->part->part_no ?? '-' }}</span>
            &nbsp;·&nbsp; {{ $stock->material->name ?? $stock->part->part_name ?? '-' }}
            &nbsp;·&nbsp; {{ $stock->warehouse->name ?? '-' }}
        </div>
    </div>
</div>

<div style="display:grid; grid-template-columns: 320px 1fr; gap:20px; align-items:start;">
    <!-- Info Panel -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-info-circle" style="color:var(--accent);margin-right:8px;"></i>Informasi Stok</span>
        </div>
        <div style="padding:0;">
            @php
                $rows = [
                    ['Gudang',           $stock->warehouse->name ?? '-'],
                    ['Item Name',        $stock->material->name ?? $stock->part->part_name ?? '-'],
                    ['Kode Item',        $stock->material->code ?? $stock->part->part_no ?? '-'],
                    ['Stok Minimum',     number_format($stock->minimum_stock, 2)],
                    ['Stok Maksimum',    $stock->max_stock ? number_format($stock->max_stock, 2) : '-'],
                    ['Stok Saat Ini',    number_format($stock->current_stock, 2)],
                ];
            @endphp
            @foreach($rows as [$label, $value])
            <div style="display:flex; justify-content:space-between; align-items:center;
                        padding:11px 20px; border-bottom:1px solid var(--border); gap:12px;">
                <span style="font-size:12px; color:var(--text-muted); white-space:nowrap;">{{ $label }}</span>
                <span style="font-size:13px; color:var(--text); font-weight:500; text-align:right;">{{ $value }}</span>
            </div>
            @endforeach
        </div>
        <div style="padding:14px 20px; background:var(--surface-2);">
            @if($stock->current_stock <= 0)
                <span class="badge badge-danger"><i class="fas fa-ban"></i> Stok Kosong</span>
            @elseif($stock->current_stock <= $stock->minimum_stock)
                <span class="badge badge-warning"><i class="fas fa-triangle-exclamation"></i> Stok Rendah</span>
            @else
                <span class="badge badge-success"><i class="fas fa-check-circle"></i> Stok Normal</span>
            @endif
        </div>
    </div>

    <!-- Mutasi History -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-right-left" style="color:var(--accent);margin-right:8px;"></i>Riwayat Mutasi</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Keterangan</th>
                        <th>No. Referensi</th>
                        <th>Tipe</th>
                        <th class="text-right">Qty</th>
                        <th class="text-right">Saldo</th>
                        <th>Dibuat Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mutasis as $mutasi)
                    <tr>
                        <td style="white-space:nowrap; font-size:12px; color:var(--text-muted);">
                            {{ $mutasi->created_at->format('d M Y H:i') }}
                        </td>
                        <td style="font-size:12.5px;">{{ $mutasi->notes ?: '-' }}</td>
                        <td>
                            @if($mutasi->reference)
                                @if($mutasi->reference_type === \App\Models\GoodReceipt::class)
                                    <a href="{{ route('good-receipts.show', $mutasi->reference_id) }}" class="mono" style="color:var(--accent); font-size:11px; text-decoration:none;">{{ $mutasi->reference->gr_number ?? '-' }}</a>
                                @elseif($mutasi->reference_type === \App\Models\GoodIssue::class)
                                    <a href="{{ route('good-issues.show', $mutasi->reference_id) }}" class="mono" style="color:var(--accent); font-size:11px; text-decoration:none;">{{ $mutasi->reference->gi_number ?? '-' }}</a>
                                @else
                                    <span class="mono" style="color:var(--accent); font-size:11px;">#{{ $mutasi->reference_id }}</span>
                                @endif
                            @else
                                <span style="color:var(--text-dim);">—</span>
                            @endif
                        </td>
                        <td>
                            @if($mutasi->type === 'in')
                                <span class="badge badge-in"><i class="fas fa-arrow-down fa-xs"></i> Masuk</span>
                            @else
                                <span class="badge badge-out"><i class="fas fa-arrow-up fa-xs"></i> Keluar</span>
                            @endif
                        </td>
                        <td class="text-right">
                            @if($mutasi->type === 'in')
                                <span class="stock-in">+{{ number_format($mutasi->quantity, 2) }}</span>
                            @else
                                <span class="stock-out">-{{ number_format($mutasi->quantity, 2) }}</span>
                            @endif
                        </td>
                        <td class="text-right" style="font-weight:600;">
                            {{ number_format($mutasi->balance, 2) }}
                        </td>
                        <td style="font-size:12px; color:var(--text-muted);">
                            {{ $mutasi->creator->name ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state" style="padding:40px;">
                                <i class="fas fa-inbox"></i>
                                <h4>Belum Ada Mutasi</h4>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($mutasis->hasPages())
        <div class="pagination-wrap" style="padding:16px 20px;">
            {{ $mutasis->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
