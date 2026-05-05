@extends('layouts.app')
@section('title', 'Detail Supplier')
@section('topbar-title', 'Master Data')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('suppliers.index') }}">Data Supplier</a>
    <span class="sep">/</span>
    <span>{{ $supplier->name }}</span>
</div>

<div class="page-header">
    <div style="display:flex;align-items:center;gap:12px;">
        <div class="page-title">{{ $supplier->name }}</div>
        @if($supplier->is_active)
            <span class="badge badge-success">Aktif</span>
        @else
            <span class="badge badge-muted">Nonaktif</span>
        @endif
    </div>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-secondary">
            <i class="fas fa-pen"></i> Edit
        </a>
        <form action="{{ route('suppliers.toggle-active', $supplier) }}" method="POST">
            @csrf @method('PATCH')
            <button type="submit" class="btn {{ $supplier->is_active ? 'btn-warning' : 'btn-success' }}">
                <i class="fas fa-{{ $supplier->is_active ? 'ban' : 'check' }}"></i>
                {{ $supplier->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
            </button>
        </form>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success" style="margin-bottom:16px;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start;">
    <div style="display:flex;flex-direction:column;gap:20px;">
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-building" style="color:var(--accent);margin-right:8px;"></i> Informasi Supplier</span>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">
                @foreach([
                    ['label'=>'Nama Supplier','value'=>$supplier->name,'bold'=>true],
                    ['label'=>'Kontak Person','value'=>$supplier->contact_person],
                    ['label'=>'Telepon','value'=>$supplier->phone],
                    ['label'=>'Email','value'=>$supplier->email],
                    ['label'=>'NPWP','value'=>$supplier->npwp],
                    ['label'=>'Alamat','value'=>$supplier->address],
                ] as $row)
                <div>
                    <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;letter-spacing:.5px;">{{ $row['label'] }}</div>
                    <div style="margin-top:3px;{{ isset($row['bold']) ? 'font-weight:600;' : 'color:var(--text-muted);' }}">
                        {{ $row['value'] ?: '—' }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        @if($supplier->bank_name || $supplier->bank_account)
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-landmark" style="color:var(--accent-2);margin-right:8px;"></i> Informasi Bank</span>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">
                @foreach([
                    ['label'=>'Nama Bank','value'=>$supplier->bank_name],
                    ['label'=>'Nomor Rekening','value'=>$supplier->bank_account],
                    ['label'=>'Atas Nama','value'=>$supplier->bank_account_name],
                ] as $row)
                <div>
                    <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;letter-spacing:.5px;">{{ $row['label'] }}</div>
                    <div style="margin-top:3px;color:var(--text-muted);">{{ $row['value'] ?: '—' }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div>
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-file-invoice" style="color:var(--warning);margin-right:8px;"></i> Riwayat Purchase Order</span>
            </div>
            @if($supplier->purchaseOrders->isEmpty())
            <div class="card-body" style="text-align:center;color:var(--text-dim);padding:32px;">
                Belum ada PO dari supplier ini.
            </div>
            @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>No. PO</th>
                            <th>Tanggal</th>
                            <th class="text-right">Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($supplier->purchaseOrders as $po)
                        <tr>
                            <td>
                                <a href="{{ route('purchase-orders.show', $po) }}"
                                   style="color:var(--accent);font-family:monospace;font-size:12px;">
                                    {{ $po->document_no }}
                                </a>
                            </td>
                            <td style="font-size:12px;color:var(--text-muted);">{{ $po->order_date->format('d M Y') }}</td>
                            <td class="text-right" style="font-size:12px;font-weight:600;">
                                Rp {{ number_format($po->total_amount, 0, ',', '.') }}
                            </td>
                            <td><span class="badge badge-{{ $po->status_color }}">{{ $po->status_label }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection