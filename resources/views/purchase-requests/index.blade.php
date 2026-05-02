@extends('layouts.app')
@section('title', 'Purchase Request')
@section('topbar-title', 'Purchase Request')
@section('topbar-actions')
    <span style="font-size:12px; color: var(--text-muted);">
        <i class="fas fa-clock"></i>
        {{ now()->format('d M Y, H:i') }}
    </span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Purchase Request</div>
        <div class="page-subtitle">Pengajuan permintaan pembelian material</div>
    </div>
    <a href="{{ route('purchase-requests.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Buat PR Baru
    </a>
</div>

{{-- Stats Row --}}
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
    <div class="card" style="padding:16px 20px; display:flex; align-items:center; gap:14px; border-color:var(--warning-bg);">
        <div style="width:40px;height:40px;border-radius:10px;background:var(--warning-bg);display:flex;align-items:center;justify-content:center;color:var(--warning);font-size:16px;">
            <i class="fas fa-clock"></i>
        </div>
        <div>
            <div style="font-size:22px;font-weight:700;color:var(--warning);">{{ $stats['submitted'] }}</div>
            <div style="font-size:11px;color:var(--text-muted);">Menunggu Review</div>
        </div>
    </div>
    <div class="card" style="padding:16px 20px; display:flex; align-items:center; gap:14px; border-color:var(--success-bg);">
        <div style="width:40px;height:40px;border-radius:10px;background:var(--success-bg);display:flex;align-items:center;justify-content:center;color:var(--success);font-size:16px;">
            <i class="fas fa-check-circle"></i>
        </div>
        <div>
            <div style="font-size:22px;font-weight:700;color:var(--success);">{{ $stats['approved'] }}</div>
            <div style="font-size:11px;color:var(--text-muted);">Disetujui</div>
        </div>
    </div>
    <div class="card" style="padding:16px 20px; display:flex; align-items:center; gap:14px; border-color:rgba(96,165,250,0.15);">
        <div style="width:40px;height:40px;border-radius:10px;background:rgba(96,165,250,0.1);display:flex;align-items:center;justify-content:center;color:var(--info);font-size:16px;">
            <i class="fas fa-cart-shopping"></i>
        </div>
        <div>
            <div style="font-size:22px;font-weight:700;color:var(--info);">{{ $stats['ordered'] }}</div>
            <div style="font-size:11px;color:var(--text-muted);">Sudah Dipesan</div>
        </div>
    </div>
</div>

{{-- Table Card --}}
<div class="card">
    <div class="card-header" style="flex-wrap:wrap; gap:12px;">
        <form method="GET" class="search-bar" style="flex:1; min-width:0;">
            <div class="search-input-wrap">
                <i class="fas fa-search"></i>
                <input type="text" name="search" class="form-control"
                       placeholder="Cari no. dokumen, pemohon, departemen..." value="{{ request('search') }}">
            </div>
            <select name="status" class="form-control" style="width:170px;">
                <option value="">Semua Status</option>
                <option value="draft"     {{ request('status') === 'draft'     ? 'selected' : '' }}>Draft</option>
                <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Menunggu Review</option>
                <option value="approved"  {{ request('status') === 'approved'  ? 'selected' : '' }}>Disetujui</option>
                <option value="rejected"  {{ request('status') === 'rejected'  ? 'selected' : '' }}>Ditolak</option>
                <option value="ordered"   {{ request('status') === 'ordered'   ? 'selected' : '' }}>Sudah Dipesan</option>
            </select>
            <input type="date" name="date_from" class="form-control" style="width:150px;" value="{{ request('date_from') }}">
            <input type="date" name="date_to"   class="form-control" style="width:150px;" value="{{ request('date_to') }}">
            <button type="submit" class="btn btn-secondary"><i class="fas fa-filter"></i> Filter</button>
            @if(request()->hasAny(['search','status','date_from','date_to']))
                <a href="{{ route('purchase-requests.index') }}" class="btn btn-ghost"><i class="fas fa-times"></i></a>
            @endif
        </form>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No. Dokumen</th>
                    <th>Tanggal</th>
                    <th>Pemohon</th>
                    <th>Departemen</th>
                    <th>Keperluan</th>
                    <th class="text-center">Item</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $pr)
                <tr>
                    <td>
                        <span class="mono" style="color:var(--accent); font-size:12px;">{{ $pr->document_no }}</span>
                    </td>
                    <td style="white-space:nowrap; font-size:12px; color:var(--text-muted);">
                        {{ $pr->request_date->format('d M Y') }}
                    </td>
                    <td style="font-weight:500;">{{ $pr->requested_by_name }}</td>
                    <td>
                        @if($pr->department)
                            <span class="badge badge-muted">{{ $pr->department }}</span>
                        @else
                            <span style="color:var(--text-dim);">—</span>
                        @endif
                    </td>
                    <td style="max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; font-size:12.5px; color:var(--text-muted);">
                        {{ $pr->purpose ?: '—' }}
                    </td>
                    <td class="text-center">
                        <span class="badge badge-info">{{ $pr->items->count() }} item</span>
                    </td>
                    <td class="text-center">
                        @php $color = $pr->status_color @endphp
                        <span class="badge badge-{{ $color }}">
                            @if($pr->status === 'draft')     <i class="fas fa-file-pen"></i>
                            @elseif($pr->status === 'submitted') <i class="fas fa-clock"></i>
                            @elseif($pr->status === 'approved')  <i class="fas fa-check"></i>
                            @elseif($pr->status === 'rejected')  <i class="fas fa-times"></i>
                            @elseif($pr->status === 'ordered')   <i class="fas fa-cart-shopping"></i>
                            @endif
                            {{ $pr->status_label }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div style="display:flex; gap:6px; justify-content:center;">
                            <a href="{{ route('purchase-requests.show', $pr) }}" class="btn btn-ghost btn-xs" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($pr->canEdit())
                            <a href="{{ route('purchase-requests.edit', $pr) }}" class="btn btn-ghost btn-xs" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            @endif
                            <a href="{{ route('purchase-requests.print', $pr) }}" target="_blank" class="btn btn-ghost btn-xs" title="Print">
                                <i class="fas fa-print"></i>
                            </a>
                            @if($pr->canEdit())
                            <form action="{{ route('purchase-requests.destroy', $pr) }}" method="POST"
                                  onsubmit="return confirm('Yakin hapus PR ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-danger" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="fas fa-cart-plus"></i>
                            <h4>Belum Ada Purchase Request</h4>
                            <p>Buat permintaan pembelian pertama Anda</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($requests->hasPages())
    <div class="pagination-wrap">
        <div class="pagination-info">
            Menampilkan {{ $requests->firstItem() }}–{{ $requests->lastItem() }} dari {{ $requests->total() }}
        </div>
        {{ $requests->links() }}
    </div>
    @endif
</div>
@endsection