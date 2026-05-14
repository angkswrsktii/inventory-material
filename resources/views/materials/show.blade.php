@extends('layouts.app')

@section('title', 'Detail Material')
@section('topbar-title', 'Detail Material')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('materials.index') }}">Data Material</a>
    <span class="sep">/</span>
    <span>{{ $material->name }}</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">{{ $material->name }}</div>
        <div class="page-subtitle">
            <span class="mono" style="color:var(--accent);">{{ $material->code }}</span>
            &nbsp;·&nbsp; {{ $material->supplier->name ?? 'Supplier tidak diset' }}
        </div>
    </div>
    <div style="display:flex; gap:10px;">
        @if(auth()->user()?->isAdmin())
        <a href="{{ route('materials.edit', $material) }}" class="btn btn-primary">
            <i class="fas fa-pen"></i> Edit
        </a>
        @endif
    </div>
</div>

<div style="display:grid; grid-template-columns: 320px 1fr; gap:20px; align-items:start;">
    <!-- Info Panel -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-info-circle" style="color:var(--accent);margin-right:8px;"></i>Informasi Material</span>
        </div>
        <div style="padding:0;">
            @php
                $rows = [
                    ['Kode Barang',      $material->code],
                    ['Nama Material',    $material->name],
                    ['Spesifikasi',      $material->specification ?: '-'],
                    ['Satuan',           $material->unit],
                    ['Supplier',         $material->supplier->name ?? '-'],
                    ['Panjang Material', $material->panjang_material ? number_format($material->panjang_material, 2).' mm' : '-'],
                    ['Status',           $material->is_active ? 'Aktif' : 'Non-Aktif'],
                ];
            @endphp
            @foreach($rows as [$label, $value])
            <div style="display:flex; justify-content:space-between; align-items:center;
                        padding:11px 20px; border-bottom:1px solid var(--border); gap:12px;">
                <span style="font-size:12px; color:var(--text-muted); white-space:nowrap;">{{ $label }}</span>
                <span style="font-size:13px; color:var(--text); font-weight:500; text-align:right;">{{ $value }}</span>
            </div>
            @endforeach
            @if($material->description)
            <div style="padding:12px 20px;">
                <div style="font-size:12px; color:var(--text-muted); margin-bottom:5px;">Keterangan</div>
                <div style="font-size:13px; color:var(--text);">{{ $material->description }}</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Stock Locations -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-boxes-stacked" style="color:var(--accent);margin-right:8px;"></i>Stok di Gudang</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Gudang</th>
                        <th class="text-right">Min. Stok</th>
                        <th class="text-right">Maks. Stok</th>
                        <th class="text-right">Stok Saat Ini</th>
                        <th width="80" class="text-center">Mutasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($material->stocks as $stock)
                    <tr>
                        <td style="font-weight:500;">{{ $stock->warehouse->name ?? '-' }}</td>
                        <td class="text-right" style="color:var(--text-muted);">{{ number_format($stock->minimum_stock, 2) }} {{ $material->unit }}</td>
                        <td class="text-right" style="color:var(--text-muted);">{{ number_format($stock->max_stock, 2) }} {{ $material->unit }}</td>
                        <td class="text-right" style="font-weight:600; color: {{ $stock->current_stock <= 0 ? 'var(--danger)' : ($stock->current_stock <= $stock->minimum_stock ? 'var(--warning)' : 'var(--success)') }}">
                            {{ number_format($stock->current_stock, 2) }} {{ $material->unit }}
                        </td>
                        <td class="text-center">
                            <a href="{{ route('inventory-stocks.show', $stock) }}" class="btn btn-ghost btn-sm" title="Lihat Mutasi"><i class="fas fa-list"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state" style="padding:40px;">
                                <i class="fas fa-inbox"></i>
                                <h4>Belum Ada Stok</h4>
                                <p>Material ini belum tersedia di gudang mana pun.</p>
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