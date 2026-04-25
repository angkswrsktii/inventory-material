@extends('layouts.app')
@section('title', 'Kartu Stok — ' . $material->name)
@section('topbar-title', 'Kartu Stok')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('stock-cards.index') }}">Kartu Stok</a>
    <span class="sep">/</span>
    <span>{{ $material->name }}</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">Kartu Stok: {{ $material->name }}</div>
        <div class="page-subtitle">
            <span class="mono" style="color:var(--accent);">{{ $material->code }}</span>
            &nbsp;·&nbsp; {{ $material->unit }}
            &nbsp;·&nbsp; {{ $material->supplier ?? 'Supplier tidak diset' }}
        </div>
    </div>
    <div style="display:flex; gap:10px;">
        <a href="{{ route('reports.print.stock-card', $material) }}" target="_blank" class="btn btn-ghost">
            <i class="fas fa-print"></i> Print Kartu
        </a>
        <a href="{{ route('stock-cards.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Input Transaksi
        </a>
    </div>
</div>

<!-- Stat Cards -->
<div class="stats-grid" style="grid-template-columns:repeat(4,1fr); margin-bottom:20px;">
    <div class="stat-card {{ $material->current_stock <= 0 ? 'red' : ($material->current_stock <= $material->minimum_stock ? 'yellow' : 'green') }}">
        <div class="stat-icon"><i class="fas fa-cubes"></i></div>
        <div class="stat-value">{{ number_format($material->current_stock, 2) }}</div>
        <div class="stat-label">Stok Saat Ini</div>
    </div>
    <div class="stat-card blue">
        <div class="stat-icon"><i class="fas fa-arrow-down"></i></div>
        <div class="stat-value">{{ number_format($stockCards->sum('quantity_in'), 2) }}</div>
        <div class="stat-label">Total Masuk</div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon"><i class="fas fa-arrow-up"></i></div>
        <div class="stat-value">{{ number_format($stockCards->sum('quantity_out'), 2) }}</div>
        <div class="stat-label">Total Keluar</div>
    </div>
    <div class="stat-card purple">
        <div class="stat-icon"><i class="fas fa-list"></i></div>
        <div class="stat-value">{{ $stockCards->total() }}</div>
        <div class="stat-label">Total Transaksi</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title">
            <i class="fas fa-table-list" style="color:var(--accent);margin-right:8px;"></i>
            Riwayat Kartu Stok
        </span>
        <div style="display:flex; gap:8px; align-items:center;">
            @if($material->current_stock <= 0)
                <span class="badge badge-danger"><i class="fas fa-ban"></i> Stok Kosong</span>
            @elseif($material->current_stock <= $material->minimum_stock)
                <span class="badge badge-warning"><i class="fas fa-triangle-exclamation"></i> Stok Rendah</span>
            @else
                <span class="badge badge-success"><i class="fas fa-check"></i> Stok Normal</span>
            @endif
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Tipe</th>
                    <th>Sumber / Keterangan</th>
                    <th>No. Referensi</th>
                    <th class="text-right">Masuk</th>
                    <th class="text-right">Keluar</th>
                    <th class="text-right">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stockCards as $i => $sc)
                <tr>
                    <td style="color:var(--text-dim);">{{ $stockCards->firstItem() + $i }}</td>
                    <td style="white-space:nowrap; font-size:12px; color:var(--text-muted);">
                        {{ $sc->transaction_date->format('d M Y') }}
                    </td>
                    <td>
                        @if($sc->type === 'in')
                            <span class="badge badge-in"><i class="fas fa-arrow-down fa-xs"></i> Masuk</span>
                        @else
                            <span class="badge badge-out"><i class="fas fa-arrow-up fa-xs"></i> Keluar</span>
                        @endif
                    </td>
                    <td style="font-size:12.5px; color:var(--text-muted);">
                        {{ $sc->source ?: '-' }}
                        @if($sc->notes)
                            <div style="font-size:11px;">{{ $sc->notes }}</div>
                        @endif
                    </td>
                    <td>
                        @if($sc->reference_no)
                            <span class="mono" style="color:var(--accent); font-size:11px;">{{ $sc->reference_no }}</span>
                        @else <span style="color:var(--text-dim);">—</span> @endif
                    </td>
                    <td class="text-right">
                        @if($sc->quantity_in > 0)
                            <span class="stock-in" style="font-size:14px;">+{{ number_format($sc->quantity_in, 2) }}</span>
                        @else <span style="color:var(--text-dim);">—</span> @endif
                    </td>
                    <td class="text-right">
                        @if($sc->quantity_out > 0)
                            <span class="stock-out" style="font-size:14px;">-{{ number_format($sc->quantity_out, 2) }}</span>
                        @else <span style="color:var(--text-dim);">—</span> @endif
                    </td>
                    <td class="text-right" style="font-weight:700; font-size:14px;">
                        {{ number_format($sc->balance, 2) }}
                        <span style="font-size:10px; color:var(--text-muted); font-weight:400;">{{ $material->unit }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <h4>Belum Ada Transaksi</h4>
                            <p>Mulai dengan input penerimaan barang untuk material ini</p>
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
            Menampilkan {{ $stockCards->firstItem() }}–{{ $stockCards->lastItem() }} dari {{ $stockCards->total() }} transaksi
        </div>
        {{ $stockCards->links() }}
    </div>
    @endif
</div>
@endsection