@extends('layouts.app')

@section('title', 'Good Receipt (GR)')
@section('topbar-title', 'Good Receipt (GR)')

@section('topbar-actions')
    <span style="font-size:12px; color: var(--text-muted);">
        <i class="fas fa-clock"></i>
        {{ now()->format('d M Y, H:i') }}
    </span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Good Receipt</div>
        <div class="page-subtitle">Material masuk dari supplier berdasarkan Pembelian Material</div>
    </div>
    <a href="{{ route('good-receipts.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Terima Material Baru
    </a>
</div>

@if(session('success'))
    <div style="background:var(--success-bg); border:1px solid var(--success); color:var(--success); padding:12px 16px; border-radius:var(--radius-sm); margin-bottom:16px; display:flex; align-items:center; gap:8px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div style="background:var(--danger-bg); border:1px solid var(--danger); color:var(--danger); padding:12px 16px; border-radius:var(--radius-sm); margin-bottom:16px; display:flex; align-items:center; gap:8px;">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
@endif

<!-- Table -->
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th>No. GR</th>
                    <th>No. PO</th>
                    <th>Tanggal Terima</th>
                    <th>Penerima System</th>
                    <th>PIC Penerima</th>
                    <th>Catatan</th>
                    <th width="90">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($goodReceipts as $gr)
                <tr>
                    <td style="color:var(--text-muted);">{{ $goodReceipts->firstItem() + $loop->index }}</td>
                    <td>
                        <span style="font-family:'Syne',sans-serif; font-size:12px; font-weight:600; color:var(--accent); background:var(--accent-glow); padding:3px 8px; border-radius:4px; white-space:nowrap;">
                            {{ $gr->gr_number }}
                        </span>
                    </td>
                    <td>
                        @if($gr->purchaseOrder)
                            <a href="{{ route('purchase-orders.show', $gr->purchaseOrder) }}" class="mono" style="color:var(--text); text-decoration:none;">{{ $gr->purchaseOrder->po_number }}</a>
                        @else
                            -
                        @endif
                    </td>
                    <td style="color:var(--text-muted);">{{ $gr->receipt_date->format('d M Y') }}</td>
                    <td>{{ $gr->receiver->name ?? '-' }}</td>
                    <td>{{ $gr->pic->name ?? '-' }}</td>
                    <td>
                        @if($gr->notes)
                            <div style="font-size:12px; color:var(--text-muted);">{{ Str::limit($gr->notes, 30) }}</div>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <div style="display:flex; gap:6px;">
                            <a href="{{ route('good-receipts.show', $gr) }}" class="btn btn-ghost btn-sm" title="Detail"><i class="fas fa-eye"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state" style="padding: 60px 20px;">
                            <i class="fas fa-truck-ramp-box"></i>
                            <h4>Belum Ada Penerimaan Material</h4>
                            <p>Mulai catat penerimaan Material dari Purchase Order</p>
                            <a href="{{ route('good-receipts.create') }}" class="btn btn-primary btn-sm" style="margin-top:12px;"><i class="fas fa-plus"></i> Terima Material Baru</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($goodReceipts->hasPages())
    <div style="padding: 16px 20px; border-top: 1px solid var(--border); display:flex; justify-content:space-between; align-items:center;">
        <div style="font-size:13px; color:var(--text-muted);">
            Menampilkan {{ $goodReceipts->firstItem() }}–{{ $goodReceipts->lastItem() }} dari {{ $goodReceipts->total() }} GR
        </div>
        <div style="display:flex; gap:6px;">
            {{ $goodReceipts->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
