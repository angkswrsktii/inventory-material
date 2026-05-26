@extends('layouts.app')

@section('title', 'Detail Purchase Request')
@section('topbar-title', __('app.nav.purchasing') . ' — ' . __('app.nav.purchase_request'))

@section('content')
<div class="breadcrumb">
    <a href="{{ route('purchase-requests.index') }}">Purchase Request</a>
    <span class="sep">/</span>
    <span>{{ $purchaseRequest->pr_number }}</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">{{ $purchaseRequest->pr_number }}</div>
        <div class="page-subtitle">Detail permintaan pembelian material</div>
    </div>
    <div style="display:flex; gap:10px;">
        
        <!-- Kondisi Jika Draft -->
        @if($purchaseRequest->status === 'draft')
            <form action="{{ route('purchase-requests.submit', $purchaseRequest) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-primary" onclick="return confirm('Ajukan PR ini untuk di-review?')"><i class="fas fa-paper-plane"></i> Ajukan PR</button>
            </form>
            <a href="{{ route('purchase-requests.edit', $purchaseRequest) }}" class="btn btn-secondary">
                <i class="fas fa-pen"></i> Edit
            </a>
        @endif

        <!-- Kondisi Jika Pending -->
        @if($purchaseRequest->status === 'pending')
            <form action="{{ route('purchase-requests.approve', $purchaseRequest) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-success" onclick="return confirm('Setujui PR ini?')"><i class="fas fa-check"></i> Setujui</button>
            </form>
            <form action="{{ route('purchase-requests.reject', $purchaseRequest) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-danger" onclick="return confirm('Tolak PR ini?')"><i class="fas fa-times"></i> Tolak</button>
            </form>
            
            <!-- Tombol Kembalikan ke Draft (Hanya muncul saat pending) -->
            <form action="{{ route('purchase-requests.revert-draft', $purchaseRequest) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-warning" onclick="return confirm('Kembalikan PR ini ke Draft agar bisa diedit?')">
                    <i class="fas fa-undo"></i> Kembalikan ke Draft
                </button>
            </form>
        @endif
        
        <!-- Print Hanya Tersedia Saat Approved -->
        @if($purchaseRequest->status != 'draft')
            <a href="{{ route('purchase-requests.print', $purchaseRequest) }}" target="_blank" class="btn btn-secondary">
                <i class="fas fa-print"></i> Print PR
            </a>
        @endif
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
                    ['No. PR',          $purchaseRequest->pr_number],
                    ['Tanggal',         $purchaseRequest->request_date->format('d M Y')],
                    ['Dibuat Oleh',     $purchaseRequest->creator->name ?? '-'],
                    ['Disetujui Oleh',  $purchaseRequest->approver->name ?? '-'],
                ];
            @endphp
            @foreach($rows as [$label, $value])
            <div style="display:flex; justify-content:space-between; align-items:center;
                        padding:11px 20px; border-bottom:1px solid var(--border); gap:12px;">
                <span style="font-size:12px; color:var(--text-muted); white-space:nowrap;">{{ $label }}</span>
                <span style="font-size:13px; color:var(--text); font-weight:500; text-align:right;">{{ $value }}</span>
            </div>
            @endforeach
            <div style="padding:14px 20px; background:var(--surface-2);">
                <div style="font-size:12px; color:var(--text-muted); margin-bottom:5px;">Status PR</div>
                @if($purchaseRequest->status === 'draft')
                    <span class="badge badge-secondary" style="background:#e2e8f0; color:#475569;">Draft</span>
                @elseif($purchaseRequest->status === 'pending')
                    <span class="badge badge-warning">Pending</span>
                @elseif($purchaseRequest->status === 'approved')
                    <span class="badge badge-success">Approved</span>
                @elseif($purchaseRequest->status === 'rejected')
                    <span class="badge badge-danger">Rejected</span>
                @elseif($purchaseRequest->status === 'completed')
                    <span class="badge badge-primary">Completed (PO)</span>
                @endif
            </div>
            @if($purchaseRequest->notes)
            <div style="padding:12px 20px;">
                <div style="font-size:12px; color:var(--text-muted); margin-bottom:5px;">Catatan Tambahan</div>
                <div style="font-size:13px; color:var(--text);">{{ $purchaseRequest->notes }}</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Items -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-cubes" style="color:var(--accent-2);margin-right:8px;"></i>Item Permintaan</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Kode</th>
                        <th>Material</th>
                        <th class="text-right">Qty</th>
                        <th>Tujuan / Keperluan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchaseRequest->items as $item)
                    <tr>
                        <td style="color:var(--text-muted);">{{ $loop->iteration }}</td>
                        <td><span class="mono" style="font-size:11px;">{{ $item->material->code ?? '-' }}</span></td>
                        <td style="font-weight:500;">{{ $item->material->name ?? '-' }}</td>
                        <td class="text-right" style="font-weight:600; color:var(--accent);">
                            {{ number_format($item->quantity, 2) }} {{ $item->material->unit ?? 'Pcs' }}
                        </td>
                        <td style="color:var(--text-muted);">{{ $item->purpose }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection