@extends('layouts.app')
@section('title', 'Purchase Order')
@section('topbar-title', 'Purchase Order')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Purchase Order</div>
        <div class="page-subtitle">Dokumen pemesanan material ke supplier</div>
    </div>
    @if(auth()->user()->isKepalaGudang() || auth()->user()->isPimpinan() || auth()->user()->isAdmin())
    <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Buat PO
    </a>
    @endif
</div>

{{-- Stats --}}
<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:20px;">
    <div class="card" style="padding:16px 20px; display:flex; align-items:center; gap:14px;">
        <div style="width:40px;height:40px;border-radius:10px;background:var(--surface-2);display:flex;align-items:center;justify-content:center;color:var(--text-muted);font-size:16px;">
            <i class="fas fa-file-pen"></i>
        </div>
        <div>
            <div style="font-size:22px;font-weight:700;color:var(--text);">{{ $stats['draft'] }}</div>
            <div style="font-size:11px;color:var(--text-muted);">Draft</div>
        </div>
    </div>
    <div class="card" style="padding:16px 20px; display:flex; align-items:center; gap:14px; border-color:rgba(96,165,250,0.2);">
        <div style="width:40px;height:40px;border-radius:10px;background:rgba(96,165,250,0.1);display:flex;align-items:center;justify-content:center;color:var(--info);font-size:16px;">
            <i class="fas fa-paper-plane"></i>
        </div>
        <div>
            <div style="font-size:22px;font-weight:700;color:var(--info);">{{ $stats['sent'] }}</div>
            <div style="font-size:11px;color:var(--text-muted);">Terkirim</div>
        </div>
    </div>
    <div class="card" style="padding:16px 20px; display:flex; align-items:center; gap:14px; border-color:var(--warning-bg);">
        <div style="width:40px;height:40px;border-radius:10px;background:var(--warning-bg);display:flex;align-items:center;justify-content:center;color:var(--warning);font-size:16px;">
            <i class="fas fa-truck"></i>
        </div>
        <div>
            <div style="font-size:22px;font-weight:700;color:var(--warning);">{{ $stats['partial'] }}</div>
            <div style="font-size:11px;color:var(--text-muted);">Sebagian Diterima</div>
        </div>
    </div>
    <div class="card" style="padding:16px 20px; display:flex; align-items:center; gap:14px; border-color:var(--success-bg);">
        <div style="width:40px;height:40px;border-radius:10px;background:var(--success-bg);display:flex;align-items:center;justify-content:center;color:var(--success);font-size:16px;">
            <i class="fas fa-circle-check"></i>
        </div>
        <div>
            <div style="font-size:22px;font-weight:700;color:var(--success);">{{ $stats['received'] }}</div>
            <div style="font-size:11px;color:var(--text-muted);">Selesai Diterima</div>
        </div>
    </div>
</div>

<div class="card">
    {{-- Filter --}}
    <div class="card-header">
        <form method="GET" style="display:flex; gap:10px; flex-wrap:wrap; width:100%;">
            <div class="search-input-wrap" style="flex:1; min-width:180px;">
                <i class="fas fa-search"></i>
                <input type="text" name="search" class="form-control"
                       placeholder="Cari No. PO atau supplier..." value="{{ request('search') }}">
            </div>
            <select name="status" class="form-control" style="width:160px;">
                <option value="">Semua Status</option>
                <option value="draft"    {{ request('status') === 'draft'    ? 'selected' : '' }}>Draft</option>
                <option value="sent"     {{ request('status') === 'sent'     ? 'selected' : '' }}>Terkirim</option>
                <option value="partial"  {{ request('status') === 'partial'  ? 'selected' : '' }}>Sebagian Diterima</option>
                <option value="received" {{ request('status') === 'received' ? 'selected' : '' }}>Selesai</option>
                <option value="cancelled"{{ request('status') === 'cancelled'? 'selected' : '' }}>Dibatalkan</option>
            </select>
            <input type="date" name="date_from" class="form-control" style="width:150px;" value="{{ request('date_from') }}">
            <input type="date" name="date_to"   class="form-control" style="width:150px;" value="{{ request('date_to') }}">
            <button type="submit" class="btn btn-secondary"><i class="fas fa-filter"></i> Filter</button>
            @if(request()->hasAny(['search','status','date_from','date_to']))
                <a href="{{ route('purchase-orders.index') }}" class="btn btn-ghost"><i class="fas fa-times"></i> Reset</a>
            @endif
        </form>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No. PO</th>
                    <th>Tanggal Order</th>
                    <th>Supplier</th>
                    <th>Dari PR</th>
                    <th>Estimasi Terima</th>
                    <th class="text-right">Total</th>
                    <th>Status</th>
                    <th>Dibuat Oleh</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $po)
                <tr>
                    <td>
                        <span class="mono" style="color:var(--accent); font-size:12px;">{{ $po->document_no }}</span>
                    </td>
                    <td style="font-size:12px; color:var(--text-muted); white-space:nowrap;">
                        {{ $po->order_date->format('d M Y') }}
                    </td>
                    <td>
                        <div style="font-weight:500;">{{ $po->supplier_name }}</div>
                        @if($po->supplier_contact)
                            <div style="font-size:11px; color:var(--text-muted);">{{ $po->supplier_contact }}</div>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('purchase-requests.show', $po->purchase_request_id) }}"
                           style="color:var(--accent); font-size:12px; text-decoration:none; font-family:monospace;">
                            {{ $po->purchaseRequest->document_no ?? '-' }}
                        </a>
                    </td>
                    <td style="font-size:12px; color:var(--text-muted);">
                        {{ $po->expected_date ? $po->expected_date->format('d M Y') : '—' }}
                    </td>
                    <td class="text-right" style="font-weight:600;">
                        @if($po->total_amount)
                            Rp {{ number_format($po->total_amount, 0, ',', '.') }}
                        @else
                            <span style="color:var(--text-dim);">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-{{ $po->status_color }}">
                            {{ $po->status_label }}
                        </span>
                    </td>
                    <td style="font-size:12px; color:var(--text-muted);">
                        {{ $po->creator->name ?? '—' }}
                    </td>
                    <td class="text-center">
                        <div style="display:flex; gap:6px; justify-content:center;">
                            <a href="{{ route('purchase-orders.show', $po) }}" class="btn btn-ghost btn-xs" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('purchase-orders.print', $po) }}" target="_blank" class="btn btn-ghost btn-xs" title="Print">
                                <i class="fas fa-print"></i>
                            </a>
                            @if($po->canSend() && (auth()->user()->isPimpinan() || auth()->user()->isAdmin()))
                            <form action="{{ route('purchase-orders.send', $po) }}" method="POST"
                                  onsubmit="return confirm('Tandai PO ini sebagai terkirim ke supplier?')">
                                @csrf
                                <button type="submit" class="btn btn-xs" style="background:var(--accent-glow);color:var(--accent);border:1px solid var(--border);" title="Kirim ke Supplier">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <i class="fas fa-file-circle-plus"></i>
                            <h4>Belum Ada Purchase Order</h4>
                            <p>Buat PO dari Purchase Request yang sudah disetujui</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
    <div class="pagination-wrap">
        <div class="pagination-info">
            Menampilkan {{ $orders->firstItem() }}–{{ $orders->lastItem() }} dari {{ $orders->total() }}
        </div>
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection