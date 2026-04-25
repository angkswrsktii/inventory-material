@extends('layouts.app')
@section('title', 'Laporan Transaksi')
@section('topbar-title', 'Laporan Transaksi')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Laporan Transaksi</div>
        <div class="page-subtitle">Riwayat transaksi masuk dan keluar raw material</div>
    </div>
    <button onclick="window.print()" class="btn btn-ghost no-print">
        <i class="fas fa-print"></i> Print
    </button>
</div>

<div class="card" style="margin-bottom:20px;">
    <div class="card-body" style="padding:16px 20px;">
        <form method="GET" class="search-bar">
            <select name="material_id" class="form-control" style="width:220px;">
                <option value="">Semua Material</option>
                @foreach($materials as $m)
                    <option value="{{ $m->id }}" {{ request('material_id') == $m->id ? 'selected' : '' }}>
                        {{ $m->name }}
                    </option>
                @endforeach
            </select>
            <select name="type" class="form-control" style="width:140px;">
                <option value="">Semua Tipe</option>
                <option value="in"  {{ request('type') === 'in'  ? 'selected' : '' }}>Masuk</option>
                <option value="out" {{ request('type') === 'out' ? 'selected' : '' }}>Keluar</option>
            </select>
            <input type="date" name="date_from" class="form-control" style="width:155px;" value="{{ request('date_from') }}">
            <span style="color:var(--text-muted); font-size:12px;">s/d</span>
            <input type="date" name="date_to"   class="form-control" style="width:155px;" value="{{ request('date_to') }}">
            <button type="submit" class="btn btn-secondary"><i class="fas fa-filter"></i> Filter</button>
            @if(request()->hasAny(['material_id','type','date_from','date_to']))
                <a href="{{ route('reports.transactions') }}" class="btn btn-ghost"><i class="fas fa-times"></i> Reset</a>
            @endif
        </form>
    </div>
</div>

<!-- Summary -->
<div class="stats-grid" style="grid-template-columns:repeat(3,1fr); margin-bottom:20px;">
    <div class="stat-card green">
        <div class="stat-icon"><i class="fas fa-arrow-down"></i></div>
        <div class="stat-value">{{ number_format($summary['total_in'], 2) }}</div>
        <div class="stat-label">Total Masuk (periode ini)</div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon"><i class="fas fa-arrow-up"></i></div>
        <div class="stat-value">{{ number_format($summary['total_out'], 2) }}</div>
        <div class="stat-label">Total Keluar (periode ini)</div>
    </div>
    <div class="stat-card blue">
        <div class="stat-icon"><i class="fas fa-list"></i></div>
        <div class="stat-value">{{ $transactions->total() }}</div>
        <div class="stat-label">Jumlah Transaksi</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-right-left" style="color:var(--accent);margin-right:8px;"></i>Detail Transaksi</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kode</th>
                    <th>Nama Material</th>
                    <th>Tipe</th>
                    <th>Sumber</th>
                    <th>No. Referensi</th>
                    <th class="text-right">Masuk</th>
                    <th class="text-right">Keluar</th>
                    <th class="text-right">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $tx)
                <tr>
                    <td style="white-space:nowrap; font-size:12px; color:var(--text-muted);">{{ $tx->transaction_date->format('d M Y') }}</td>
                    <td><span class="mono" style="color:var(--accent); font-size:11px;">{{ $tx->material->code ?? '-' }}</span></td>
                    <td style="font-weight:500;">{{ $tx->material->name ?? '-' }}</td>
                    <td>
                        @if($tx->type === 'in')
                            <span class="badge badge-in"><i class="fas fa-arrow-down fa-xs"></i> Masuk</span>
                        @else
                            <span class="badge badge-out"><i class="fas fa-arrow-up fa-xs"></i> Keluar</span>
                        @endif
                    </td>
                    <td style="font-size:12px; color:var(--text-muted);">{{ $tx->source ?: '—' }}</td>
                    <td><span class="mono" style="font-size:11px; color:var(--accent);">{{ $tx->reference_no ?: '—' }}</span></td>
                    <td class="text-right">
                        @if($tx->quantity_in > 0) <span class="stock-in">+{{ number_format($tx->quantity_in,2) }}</span>
                        @else <span style="color:var(--text-dim);">—</span> @endif
                    </td>
                    <td class="text-right">
                        @if($tx->quantity_out > 0) <span class="stock-out">-{{ number_format($tx->quantity_out,2) }}</span>
                        @else <span style="color:var(--text-dim);">—</span> @endif
                    </td>
                    <td class="text-right" style="font-weight:600;">{{ number_format($tx->balance,2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-state"><i class="fas fa-inbox"></i><h4>Tidak ada transaksi ditemukan</h4></div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($transactions->hasPages())
    <div class="pagination-wrap">
        <div class="pagination-info">{{ $transactions->firstItem() }}–{{ $transactions->lastItem() }} dari {{ $transactions->total() }}</div>
        {{ $transactions->links() }}
    </div>
    @endif
</div>
@endsection