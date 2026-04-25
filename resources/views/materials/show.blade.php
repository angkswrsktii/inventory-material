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
            &nbsp;·&nbsp; {{ $material->supplier ?? 'Supplier tidak diset' }}
        </div>
    </div>
    <div style="display:flex; gap:10px;">
        <a href="{{ route('stock-cards.show', $material) }}" class="btn btn-secondary">
            <i class="fas fa-table-list"></i> Kartu Stok
        </a>
        @if(auth()->user()?->isAdmin())
        <a href="{{ route('materials.edit', $material) }}" class="btn btn-primary">
            <i class="fas fa-pen"></i> Edit
        </a>
        @endif
    </div>
</div>

<!-- Info Cards -->
<div class="stats-grid" style="grid-template-columns: repeat(3, 1fr); margin-bottom:20px;">
    <div class="stat-card {{ $material->current_stock <= 0 ? 'red' : ($material->current_stock <= $material->minimum_stock ? 'yellow' : 'green') }}">
        <div class="stat-icon"><i class="fas fa-cubes"></i></div>
        <div class="stat-value">{{ number_format($material->current_stock, 2) }}</div>
        <div class="stat-label">Stok Saat Ini ({{ $material->unit }})</div>
    </div>
    <div class="stat-card blue">
        <div class="stat-icon"><i class="fas fa-arrow-down"></i></div>
        <div class="stat-value">{{ number_format($material->stockCards->where('type','in')->sum('quantity_in'), 2) }}</div>
        <div class="stat-label">Total Masuk</div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon"><i class="fas fa-arrow-up"></i></div>
        <div class="stat-value">{{ number_format($material->stockCards->where('type','out')->sum('quantity_out'), 2) }}</div>
        <div class="stat-label">Total Keluar</div>
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
                    ['Kode Barang',   $material->code],
                    ['Nama Material', $material->name],
                    ['Spesifikasi',   $material->specification ?: '-'],
                    ['Satuan',        $material->unit],
                    ['Supplier',      $material->supplier ?: '-'],
                    ['Stok Minimum',  number_format($material->minimum_stock, 2) . ' ' . $material->unit],
                    ['Stok Saat Ini', number_format($material->current_stock, 2) . ' ' . $material->unit],
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
        <div style="padding:14px 20px; background:var(--surface-2);">
            @if($material->current_stock <= 0)
                <span class="badge badge-danger"><i class="fas fa-ban"></i> Stok Kosong</span>
            @elseif($material->current_stock <= $material->minimum_stock)
                <span class="badge badge-warning"><i class="fas fa-triangle-exclamation"></i> Stok Rendah</span>
            @else
                <span class="badge badge-success"><i class="fas fa-check-circle"></i> Stok Normal</span>
            @endif
        </div>
    </div>

    <!-- Stock Card History -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-table-list" style="color:var(--accent);margin-right:8px;"></i>Riwayat Transaksi</span>
            <a href="{{ route('reports.print.stock-card', $material) }}" target="_blank" class="btn btn-ghost btn-sm">
                <i class="fas fa-print"></i> Print
            </a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>No. Referensi</th>
                        <th class="text-right">Masuk</th>
                        <th class="text-right">Keluar</th>
                        <th class="text-right">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stockCards as $sc)
                    <tr>
                        <td style="white-space:nowrap; font-size:12px; color:var(--text-muted);">
                            {{ $sc->transaction_date->format('d M Y') }}
                        </td>
                        <td style="font-size:12.5px;">{{ $sc->source ?: ($sc->type === 'in' ? 'Penerimaan' : 'Pengeluaran') }}</td>
                        <td>
                            @if($sc->reference_no)
                                <span class="mono" style="color:var(--accent); font-size:11px;">{{ $sc->reference_no }}</span>
                            @else
                                <span style="color:var(--text-dim);">—</span>
                            @endif
                        </td>
                        <td class="text-right">
                            @if($sc->quantity_in > 0)
                                <span class="stock-in">+{{ number_format($sc->quantity_in, 2) }}</span>
                            @else
                                <span style="color:var(--text-dim);">—</span>
                            @endif
                        </td>
                        <td class="text-right">
                            @if($sc->quantity_out > 0)
                                <span class="stock-out">-{{ number_format($sc->quantity_out, 2) }}</span>
                            @else
                                <span style="color:var(--text-dim);">—</span>
                            @endif
                        </td>
                        <td class="text-right" style="font-weight:600;">
                            {{ number_format($sc->balance, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state" style="padding:40px;">
                                <i class="fas fa-inbox"></i>
                                <h4>Belum Ada Transaksi</h4>
                                <p>Tambahkan penerimaan barang untuk material ini</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($stockCards->hasPages())
        <div class="pagination-wrap">
            <div class="pagination-info">
                Menampilkan {{ $stockCards->firstItem() }}–{{ $stockCards->lastItem() }} dari {{ $stockCards->total() }}
            </div>
            {{ $stockCards->links() }}
        </div>
        @endif
    </div>
</div>
@endsection