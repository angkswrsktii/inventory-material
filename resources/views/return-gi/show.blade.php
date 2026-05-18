@extends('layouts.app')

@section('title', 'Detail Recycle Good Issue')
@section('topbar-title', 'Recycle Good Issue')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('return-gi.index') }}">Recycle Good Issue</a>
    <span class="sep">/</span>
    <span>{{ $returnGi->return_number }}</span>
</div>

<div class="page-header">
    <div style="display:flex; justify-content:space-between; align-items:center; width:100%;">
        <div>
            <div class="page-title">{{ $returnGi->return_number }}</div>
            <div class="page-subtitle">Detail pengembalian material dari Work Order</div>
        </div>
        <div>
            <a href="{{ route('return-gi.print', $returnGi) }}" target="_blank" class="btn btn-secondary">
                <i class="fas fa-print"></i> Cetak Dokumen
            </a>
        </div>
    </div>
</div>

<div style="display:grid; grid-template-columns: 350px 1fr; gap:20px; align-items:start;">
    <!-- Info Panel -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-info-circle" style="color:var(--accent);margin-right:8px;"></i>Informasi Dokumen</span>
        </div>
        <div style="padding:0;">
            <div style="display:flex; justify-content:space-between; align-items:center; padding:11px 20px; border-bottom:1px solid var(--border); gap:12px;">
                <span style="font-size:12px; color:var(--text-muted);">No. Retur</span>
                <span style="font-size:13px; font-weight:600;">{{ $returnGi->return_number }}</span>
            </div>
            <div style="display:flex; justify-content:space-between; align-items:center; padding:11px 20px; border-bottom:1px solid var(--border); gap:12px;">
                <span style="font-size:12px; color:var(--text-muted);">Tanggal Retur</span>
                <span style="font-size:13px; font-weight:500;">{{ $returnGi->return_date->format('d M Y') }}</span>
            </div>
            
            @if($returnGi->productionQc)
            <div style="display:flex; justify-content:space-between; align-items:center; padding:11px 20px; border-bottom:1px solid var(--border); background:rgba(var(--accent-rgb), 0.05);">
                <span style="font-size:12px; color:var(--text-muted);">Dari Work Order (QC)</span>
                <a href="{{ route('production-qc.show', $returnGi->productionQc) }}" style="font-size:13px; color:var(--accent); font-weight:600; font-family:monospace;">
                    {{ $returnGi->productionQc->wo_number }}
                </a>
            </div>
            @endif

            @if($returnGi->goodIssue)
            <div style="display:flex; justify-content:space-between; align-items:center; padding:11px 20px; border-bottom:1px solid var(--border);">
                <span style="font-size:12px; color:var(--text-muted);">Sumber GI Awal</span>
                <a href="{{ route('good-issues.show', $returnGi->goodIssue) }}" style="font-size:13px; color:var(--text); font-weight:500; font-family:monospace;">
                    {{ $returnGi->goodIssue->gi_number }}
                </a>
            </div>
            @endif

            <div style="display:flex; justify-content:space-between; align-items:center; padding:11px 20px; border-bottom:1px solid var(--border); gap:12px;">
                <span style="font-size:12px; color:var(--text-muted);">Dibuat Oleh</span>
                <span style="font-size:13px; font-weight:500;">{{ $returnGi->returner->name ?? '-' }}</span>
            </div>

            <div style="padding:12px 20px;">
                <div style="font-size:12px; color:var(--text-muted); margin-bottom:5px;">Catatan Retur</div>
                <div style="font-size:13px; font-weight:500;">{{ $returnGi->notes ?: 'Tidak ada catatan' }}</div>
            </div>
        </div>
    </div>

    <!-- Items Panel -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-boxes-stacked" style="color:var(--accent-2);margin-right:8px;"></i>Rincian Material Kembali ke Gudang (Stok Mutasi IN)</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Data Material</th>
                        <th class="text-right">Qty Kembali</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($returnGi->items as $item)
                    <tr>
                        <td style="color:var(--text-muted);">{{ $loop->iteration }}</td>
                        <td>
                            <div style="font-weight:600; font-size:14px;">{{ $item->material->name ?? '-' }}</div>
                            <div style="font-size:11px; color:var(--text-muted);">Kode: {{ $item->material->code ?? '-' }}</div>
                        </td>
                        <td class="text-right" style="font-weight:800; color:var(--success); font-size:15px;">
                            +{{ number_format($item->quantity, 2) }} {{ $item->unit ?? 'Pcs' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center" style="padding: 30px;">Tidak ada item retur yang tercatat</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection