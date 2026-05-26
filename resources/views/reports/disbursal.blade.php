@extends('layouts.app')
@section('title', 'Disbursal Report')
@section('topbar-title', __('app.nav.good_issue') . ' — ' . __('app.nav.disbursal_report'))
@section('content')

<div class="page-header">
    <div>
        <div class="page-title">Disbursal Report</div>
        <div class="page-subtitle">Rekap dokumen pengeluaran material dari gudang berdasarkan Material Keluar</div>
    </div>
    <button onclick="window.print()" class="btn btn-ghost no-print"><i class="fas fa-print"></i> Print</button>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-file-lines" style="color:var(--accent);margin-right:8px;"></i>Daftar Good Issue</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No. GI</th>
                    <th>Tanggal Keluar</th>
                    <th>PIC</th>
                    <th>Target Part</th>
                    <th>Tujuan / Purpose</th>
                    <th>Item Material</th>
                    <th class="text-center no-print">Print</th>
                </tr>
            </thead>
            <tbody>
                @forelse($withdrawals as $w)
                <tr>
                    <td><span class="mono" style="color:var(--accent); font-weight:bold;">{{ $w->gi_number }}</span></td>
                    <td style="font-size:12px; color:var(--text-muted);">{{ $w->issue_date->format('d M Y') }}</td>
                    <td style="font-weight:500;">{{ $w->pic->name ?? '-' }}</td>
                    <td>{{ $w->part->part_name ?? '-' }}</td>
                    <td style="font-size:12px; color:var(--text-muted);">{{ Str::limit($w->purpose, 40) }}</td>
                    <td>
                        @foreach($w->items as $item)
                            <div style="font-size:11px; color:var(--text-muted);">
                                • {{ $item->material->name ?? '-' }} 
                                <span style="color:var(--danger); font-weight:bold;">(-{{ number_format($item->quantity, 2) }})</span>
                            </div>
                        @endforeach
                    </td>
                    <td class="text-center no-print">
                        <a href="{{ route('reports.print.withdrawal', $w) }}" target="_blank" class="btn btn-ghost btn-xs">
                            <i class="fas fa-print"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7"><div class="empty-state"><h4>Tidak ada data Good Issue</h4></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($withdrawals->hasPages())
    <div style="padding:15px;">{{ $withdrawals->links() }}</div>
    @endif
</div>
@endsection