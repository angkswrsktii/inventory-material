@extends('layouts.app')
@section('title', 'Laporan Pengambilan')
@section('topbar-title', 'Laporan Pengambilan')
@section('topbar-actions')
    <span style="font-size:12px; color: var(--text-muted);">
        <i class="fas fa-clock"></i>
        {{ now()->format('d M Y, H:i') }}
    </span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Laporan Pengambilan Material</div>
        <div class="page-subtitle">Rekap semua kartu pengambilan raw material</div>
    </div>
    <button onclick="window.print()" class="btn btn-ghost no-print">
        <i class="fas fa-print"></i> Print
    </button>
</div>

<div class="card" style="margin-bottom:20px;">
    <div class="card-body" style="padding:16px 20px;">
        <form method="GET" class="search-bar">
            <input type="text" name="line" class="form-control" style="width:180px;"
                   value="{{ request('line') }}" placeholder="Filter line produksi...">
            <input type="date" name="date_from" class="form-control" style="width:155px;" value="{{ request('date_from') }}">
            <span style="color:var(--text-muted); font-size:12px;">s/d</span>
            <input type="date" name="date_to"   class="form-control" style="width:155px;" value="{{ request('date_to') }}">
            <button type="submit" class="btn btn-secondary"><i class="fas fa-filter"></i> Filter</button>
            @if(request()->hasAny(['line','date_from','date_to']))
                <a href="{{ route('reports.withdrawals') }}" class="btn btn-ghost"><i class="fas fa-times"></i> Reset</a>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-file-lines" style="color:var(--accent);margin-right:8px;"></i>Daftar Pengambilan</span>
        <span style="font-size:12px; color:var(--text-muted);">{{ $withdrawals->total() }} dokumen</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No. Dokumen</th>
                    <th>Tanggal</th>
                    <th>PIC</th>
                    <th>Line</th>
                    <th>Part Name</th>
                    <th>Work Order</th>
                    <th>Material</th>
                    <th>Status</th>
                    <th class="text-center no-print">Print</th>
                </tr>
            </thead>
            <tbody>
                @forelse($withdrawals as $w)
                <tr>
                    <td><span class="mono" style="color:var(--accent); font-size:12px;">{{ $w->document_no }}</span></td>
                    <td style="white-space:nowrap; font-size:12px; color:var(--text-muted);">{{ $w->withdrawal_date->format('d M Y') }}</td>
                    <td style="font-weight:500;">{{ $w->pic }}</td>
                    <td><span class="badge badge-muted">{{ $w->line }}</span></td>
                    <td>{{ $w->part_name }}</td>
                    <td style="font-size:12px; color:var(--text-muted);">{{ $w->work_order ?: '—' }}</td>
                    <td>
                        @foreach($w->items as $item)
                            <div style="font-size:11px; color:var(--text-muted);">
                                {{ $item->material->name ?? '-' }}
                                <span style="color:var(--danger);">(-{{ number_format($item->quantity,2) }} {{ $item->material->unit ?? '' }})</span>
                            </div>
                        @endforeach
                    </td>
                    <td>
                        @if($w->status === 'approved')
                            <span class="badge badge-success"><i class="fas fa-check"></i> Approved</span>
                        @elseif($w->status === 'pending')
                            <span class="badge badge-warning">Pending</span>
                        @else
                            <span class="badge badge-danger">Rejected</span>
                        @endif
                    </td>
                    <td class="text-center no-print">
                        <a href="{{ route('reports.print.withdrawal', $w) }}" target="_blank" class="btn btn-ghost btn-xs">
                            <i class="fas fa-print"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-state"><i class="fas fa-inbox"></i><h4>Tidak ada data pengambilan</h4></div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($withdrawals->hasPages())
    <div class="pagination-wrap">
        <div class="pagination-info">{{ $withdrawals->firstItem() }}–{{ $withdrawals->lastItem() }} dari {{ $withdrawals->total() }}</div>
        {{ $withdrawals->links() }}
    </div>
    @endif
</div>
@endsection