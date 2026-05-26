@extends('layouts.app')
@section('title', 'Receiving Report')
@section('topbar-title', __('app.nav.good_receipt') . ' — ' . __('app.nav.receiving_report'))

@section('topbar-actions')
    <button onclick="window.print()" class="btn btn-ghost btn-sm no-print">
        <i class="fas fa-print"></i> Print
    </button>
@endsection

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Receiving Report</div>
        <div class="page-subtitle">Rekap penerimaan Material dari supplier berdasarkan Good Receipt</div>
    </div>
    <button onclick="window.print()" class="btn btn-ghost no-print"><i class="fas fa-print"></i> Print</button>
</div>

<!-- Filter -->
<div class="card no-print" style="margin-bottom: 20px;">
    <div class="card-body" style="padding: 16px 20px;">
        <form method="GET" action="{{ route('reports.receiving') }}" style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
            <div style="display:flex; flex-direction:column; gap:4px;">
                <label style="font-size:11px; color:var(--text-muted); text-transform:uppercase; letter-spacing:.5px;">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    style="background:var(--surface-2); border:1px solid var(--border); color:var(--text); padding:8px 12px; border-radius:var(--radius-sm); font-family:inherit; font-size:13px; outline:none;">
            </div>
            <div style="display:flex; flex-direction:column; gap:4px;">
                <label style="font-size:11px; color:var(--text-muted); text-transform:uppercase; letter-spacing:.5px;">Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    style="background:var(--surface-2); border:1px solid var(--border); color:var(--text); padding:8px 12px; border-radius:var(--radius-sm); font-family:inherit; font-size:13px; outline:none;">
            </div>
            <div style="display:flex; flex-direction:column; gap:4px;">
                <label style="font-size:11px; color:var(--text-muted); text-transform:uppercase; letter-spacing:.5px;">PIC</label>
                <select name="pic_id"
                    style="background:var(--surface-2); border:1px solid var(--border); color:var(--text); padding:8px 12px; border-radius:var(--radius-sm); font-family:inherit; font-size:13px; outline:none; min-width:180px;">
                    <option value="">-- Semua PIC --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('pic_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex; gap:8px; margin-top:18px;">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Filter</button>
                @if(request('date_from') || request('date_to') || request('pic_id'))
                    <a href="{{ route('reports.receiving') }}" class="btn btn-ghost btn-sm"><i class="fas fa-times"></i> Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-truck-ramp-box" style="color:var(--accent);margin-right:8px;"></i>Daftar Good Receipt</span>
        <span style="font-size:12px; color:var(--text-muted);">Per tanggal: {{ now()->format('d M Y, H:i') }}</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No. GR</th>
                    <th>No. PO</th>
                    <th>Tanggal Terima</th>
                    <th>PIC Penerima</th>
                    <th>Penerima System</th>
                    <th>Item Material</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($receipts as $gr)
                <tr>
                    <td>
                        <span class="mono" style="color:var(--accent); font-weight:bold;">{{ $gr->gr_number }}</span>
                    </td>
                    <td>
                        @if($gr->purchaseOrder)
                            <span class="mono" style="font-size:12px;">{{ $gr->purchaseOrder->po_number }}</span>
                        @else
                            -
                        @endif
                    </td>
                    <td style="font-size:12px; color:var(--text-muted);">{{ $gr->receipt_date->format('d M Y') }}</td>
                    <td style="font-weight:500;">{{ $gr->pic->name ?? '-' }}</td>
                    <td>{{ $gr->receiver->name ?? '-' }}</td>
                    <td>
                        @foreach($gr->items as $item)
                            <div style="font-size:11px; color:var(--text-muted);">
                                • {{ $item->material->name ?? '-' }}
                                <span style="color:var(--success); font-weight:bold;">(+{{ number_format($item->quantity, 2) }} {{ $item->unit }})</span>
                            </div>
                        @endforeach
                    </td>
                    <td style="font-size:12px; color:var(--text-muted);">{{ $gr->notes ? Str::limit($gr->notes, 40) : '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state"><i class="fas fa-inbox"></i><h4>Tidak ada data Good Receipt</h4></div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($receipts->hasPages())
    <div style="padding:15px;">{{ $receipts->links() }}</div>
    @endif
</div>
@endsection