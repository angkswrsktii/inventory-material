@extends('layouts.app')

@section('title', 'Detail Good Issue')
@section('topbar-title', 'Detail Good Issue')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('good-issues.index') }}">Good Issue</a>
    <span class="sep">/</span>
    <span>{{ $goodIssue->gi_number }}</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">{{ $goodIssue->gi_number }}</div>
        <div class="page-subtitle">Detail pengeluaran barang</div>
    </div>
    <div style="display:flex; gap:10px;">
        <a href="{{ route('reports.print.good-issue', $goodIssue) }}" target="_blank" class="btn btn-secondary">
            <i class="fas fa-print"></i> Print GI
        </a>
    </div>
</div>

<div style="display:grid; grid-template-columns: 320px 1fr; gap:20px; align-items:start;">
    <!-- Info Panel -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-info-circle" style="color:var(--accent);margin-right:8px;"></i>Informasi Dokumen</span>
        </div>
        <div style="padding:0;">
            @php
                $rows = [
                    ['No. GI',           $goodIssue->gi_number],
                    ['Target Produksi',  $goodIssue->part->part_name ?? '-'],
                    ['PIC Pemotong',     $goodIssue->pic->name ?? '-'],
                    ['Project',          $goodIssue->project->name ?? '-'],
                    ['Tanggal Keluar',   $goodIssue->issue_date->format('d M Y')],
                    ['Dikeluarkan Oleh', $goodIssue->issuer->name ?? '-'],
                ];
            @endphp
            @foreach($rows as [$label, $value])
            <div style="display:flex; justify-content:space-between; align-items:center;
                        padding:11px 20px; border-bottom:1px solid var(--border); gap:12px;">
                <span style="font-size:12px; color:var(--text-muted); white-space:nowrap;">{{ $label }}</span>
                <span style="font-size:13px; color:var(--text); font-weight:500; text-align:right;">{{ $value }}</span>
            </div>
            @endforeach
            <div style="padding:12px 20px;">
                <div style="font-size:12px; color:var(--text-muted); margin-bottom:5px;">Tujuan / Catatan</div>
                <div style="font-size:13px; color:var(--text); font-weight:500;">{{ $goodIssue->purpose }}</div>
            </div>
        </div>
    </div>

    <!-- Items -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-boxes-stacked" style="color:var(--accent-2);margin-right:8px;"></i>Item Dikeluarkan</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Item</th>
                        <th>Gudang Asal</th>
                        <th class="text-right">Qty Keluar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($goodIssue->items as $item)
                    <tr>
                        <td style="color:var(--text-muted);">{{ $loop->iteration }}</td>
                        <td>
                            <div style="font-weight:500;">{{ $item->material->name ?? '-' }}</div>
                            <div style="font-size:11px; color:var(--text-muted);">
                                {{ $item->material->code ?? '-' }}
                            </div>
                        </td>
                        <td>
                            {{ $goodIssue->warehouse->name ?? '-' }}
                        </td>
                        <td class="text-right" style="font-weight:600; color:var(--danger);">
                            -{{ number_format($item->quantity, 2) }} 
                            {{ $item->unit ?? 'Pcs' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection