@extends('layouts.app')
@section('title', 'Detail Pengambilan')
@section('topbar-title', 'Kartu Pengambilan')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('withdrawal-cards.index') }}">Kartu Pengambilan</a>
    <span class="sep">/</span>
    <span>{{ $withdrawalCard->document_no }}</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">{{ $withdrawalCard->document_no }}</div>
        <div class="page-subtitle">{{ $withdrawalCard->withdrawal_date->format('d F Y') }} &nbsp;·&nbsp; {{ $withdrawalCard->pic }}</div>
    </div>
    <div style="display:flex; gap:10px;">
        <a href="{{ route('reports.print.withdrawal', $withdrawalCard) }}" target="_blank" class="btn btn-ghost">
            <i class="fas fa-print"></i> Print
        </a>
        <a href="{{ route('withdrawal-cards.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 300px; gap:20px; align-items:start;">

    {{-- Items Table --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">
                <i class="fas fa-boxes-stacked" style="color:var(--accent);margin-right:8px;"></i>
                Material yang Diambil
            </span>
            <span class="badge badge-info">{{ $withdrawalCard->items->count() }} item</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Material</th>
                        <th>Satuan</th>
                        <th class="text-right">Stok Sebelum</th>
                        <th class="text-right">Jumlah Diambil</th>
                        <th class="text-right">Stok Sesudah</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($withdrawalCard->items as $i => $item)
                    <tr>
                        <td style="color:var(--text-dim);">{{ $i + 1 }}</td>
                        <td><span class="mono" style="color:var(--accent); font-size:11px;">{{ $item->material->code ?? '-' }}</span></td>
                        <td style="font-weight:500;">{{ $item->material->name ?? '-' }}</td>
                        <td><span class="badge badge-muted">{{ $item->material->unit ?? '-' }}</span></td>
                        <td class="text-right" style="color:var(--text-muted);">{{ number_format($item->stock_before, 2) }}</td>
                        <td class="text-right"><span class="stock-out" style="font-size:14px;">-{{ number_format($item->quantity, 2) }}</span></td>
                        <td class="text-right" style="font-weight:600;">{{ number_format($item->stock_after, 2) }}</td>
                        <td style="font-size:12px; color:var(--text-muted);">{{ $item->notes ?: '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Info Panel --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-file-invoice" style="color:var(--accent-2);margin-right:8px;"></i>Detail Dokumen</span>
            @if($withdrawalCard->status === 'approved')
                <span class="badge badge-success"><i class="fas fa-check"></i> Approved</span>
            @elseif($withdrawalCard->status === 'pending')
                <span class="badge badge-warning">Pending</span>
            @else
                <span class="badge badge-danger">Rejected</span>
            @endif
        </div>
        <div style="padding:0;">
            @php
                $rows = [
                    ['No. Dokumen',  $withdrawalCard->document_no],
                    ['Tanggal',      $withdrawalCard->withdrawal_date->format('d M Y')],
                    ['PIC',          $withdrawalCard->pic],
                    ['Line',         $withdrawalCard->line],
                    ['Part Name',    $withdrawalCard->part_name],
                    ['Work Order',   $withdrawalCard->work_order ?: '—'],
                    ['Dibuat Oleh',  $withdrawalCard->creator?->name ?? '—'],
                    ['Disetujui',    $withdrawalCard->approver?->name ?? '—'],
                ];
            @endphp
            @foreach($rows as [$label, $value])
            <div style="display:flex; justify-content:space-between; align-items:center;
                        padding:10px 18px; border-bottom:1px solid var(--border); gap:10px;">
                <span style="font-size:12px; color:var(--text-muted); white-space:nowrap;">{{ $label }}</span>
                <span style="font-size:13px; color:var(--text); font-weight:500; text-align:right;">{{ $value }}</span>
            </div>
            @endforeach
            @if($withdrawalCard->notes)
            <div style="padding:12px 18px;">
                <div style="font-size:12px; color:var(--text-muted); margin-bottom:4px;">Catatan</div>
                <div style="font-size:13px; color:var(--text);">{{ $withdrawalCard->notes }}</div>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection