@extends('layouts.app')
@section('title', 'Laporan Mutasi Transaksi')
@section('topbar-title', 'Laporan Transaksi')

@section('content')
<!-- Filter form diabaikan untuk mempersingkat tampilan, kamu bisa gunakan form filter bawaanmu -->
<div class="page-header">
    <div>
        <div class="page-title">Laporan Mutasi Transaksi</div>
        <div class="page-subtitle">Riwayat pergerakan (In/Out) raw material dari seluruh proses</div>
    </div>
    <button onclick="window.print()" class="btn btn-ghost no-print"><i class="fas fa-print"></i> Print</button>
</div>

<!-- Summary -->
<div class="stats-grid" style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:20px;">
    <div class="card" style="padding:20px; border-left:4px solid var(--success);">
        <div style="font-size:12px; color:var(--text-muted);">Total Masuk (In)</div>
        <div style="font-size:24px; font-weight:bold; color:var(--success);">{{ number_format($summary['total_in'], 2) }}</div>
    </div>
    <div class="card" style="padding:20px; border-left:4px solid var(--danger);">
        <div style="font-size:12px; color:var(--text-muted);">Total Keluar (Out)</div>
        <div style="font-size:24px; font-weight:bold; color:var(--danger);">{{ number_format($summary['total_out'], 2) }}</div>
    </div>
    <div class="card" style="padding:20px; border-left:4px solid var(--accent);">
        <div style="font-size:12px; color:var(--text-muted);">Total Transaksi</div>
        <div style="font-size:24px; font-weight:bold; color:var(--accent);">{{ $transactions->total() }}</div>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama Material</th>
                    <th>Tipe</th>
                    <th>Sumber Dokumen</th>
                    <th>Catatan (Notes)</th>
                    <th class="text-right">Masuk</th>
                    <th class="text-right">Keluar</th>
                    <th class="text-right">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $tx)
                <tr>
                    <td style="white-space:nowrap; font-size:12px; color:var(--text-muted);">{{ $tx->created_at->format('d M Y, H:i') }}</td>
                    <td style="font-weight:500;">{{ $tx->material->name ?? '-' }} <br> <span style="font-size:11px; color:var(--accent);">{{ $tx->material->code ?? '-' }}</span></td>
                    <td>
                        @if($tx->type === 'in')
                            <span class="badge badge-success"><i class="fas fa-arrow-down fa-xs"></i> Masuk</span>
                        @else
                            <span class="badge badge-danger"><i class="fas fa-arrow-up fa-xs"></i> Keluar</span>
                        @endif
                    </td>
                    <td style="font-size:12px; color:var(--text-muted);">{{ class_basename($tx->reference_type) }}</td>
                    <td style="font-size:12px;">{{ $tx->notes ?: '—' }}</td>
                    <td class="text-right">
                        @if($tx->type === 'in') <span style="color:var(--success); font-weight:bold;">+{{ number_format($tx->quantity,2) }}</span> @else <span style="color:var(--text-dim);">—</span> @endif
                    </td>
                    <td class="text-right">
                        @if($tx->type === 'out') <span style="color:var(--danger); font-weight:bold;">-{{ number_format($tx->quantity,2) }}</span> @else <span style="color:var(--text-dim);">—</span> @endif
                    </td>
                    <td class="text-right" style="font-weight:600;">{{ number_format($tx->balance,2) }}</td>
                </tr>
                @empty
                <tr><td colspan="8"><div class="empty-state"><h4>Tidak ada transaksi ditemukan</h4></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($transactions->hasPages())
    <div style="padding:15px;">{{ $transactions->links() }}</div>
    @endif
</div>
@endsection