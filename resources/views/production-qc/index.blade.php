@extends('layouts.app')
@section('title', 'Quality Control Hasil Produksi')
@section('topbar-title', __('app.nav.work_order') . ' — ' . __('app.nav.quality_check'))

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Work Order (Quality Check)</div>
        <div class="page-subtitle">Quality control Material hasil produksi dari Good Issue</div>
    </div>
    <a href="{{ route('production-qc.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Buat Work Order Baru
    </a>
</div>


{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:20px;">
    <div class="card" style="padding:20px;">
        <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;letter-spacing:.5px;">Draft</div>
        <div style="font-size:28px;font-weight:800;font-family:'Syne',sans-serif;color:var(--warning);">{{ $stats['draft'] ?? 0 }}</div>
    </div>
    <div class="card" style="padding:20px;">
        <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;letter-spacing:.5px;">Disetujui</div>
        <div style="font-size:28px;font-weight:800;font-family:'Syne',sans-serif;color:var(--success);">{{ $stats['approved'] ?? 0 }}</div>
    </div>
    <div class="card" style="padding:20px;">
        <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;letter-spacing:.5px;">Total OK (Approved)</div>
        <div style="font-size:28px;font-weight:800;font-family:'Syne',sans-serif;color:var(--success);">{{ number_format($stats['total_ok'] ?? 0, 2) }}</div>
    </div>
    <div class="card" style="padding:20px;">
        <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;letter-spacing:.5px;">Total NG (Approved)</div>
        <div style="font-size:28px;font-weight:800;font-family:'Syne',sans-serif;color:var(--danger);">{{ number_format($stats['total_ng'] ?? 0, 2) }}</div>
    </div>
</div>

<div class="card">
    <div class="card-header" style="gap:12px;flex-wrap:wrap;">
        <form method="GET" style="display:flex;gap:10px;flex:1;flex-wrap:wrap;">
            <div style="position:relative;flex:1;min-width:200px;">
                <i class="fas fa-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-dim);font-size:13px;"></i>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                       style="padding-left:36px;" placeholder="Cari no. WO, no. GI, part name...">
            </div>
            <select name="status" class="form-control" style="width:150px;">
                <option value="">Semua Status</option>
                <option value="draft"    {{ request('status')==='draft'    ? 'selected':'' }}>Draft</option>
                <option value="approved" {{ request('status')==='approved' ? 'selected':'' }}>Disetujui</option>
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control" style="width:150px;">
            <input type="date" name="date_to"   value="{{ request('date_to') }}"   class="form-control" style="width:150px;">
            <button type="submit" class="btn btn-secondary"><i class="fas fa-filter"></i> Filter</button>
            @if(request()->hasAny(['search','status','date_from','date_to']))
                <a href="{{ route('production-qc.index') }}" class="btn btn-ghost">Reset</a>
            @endif
        </form>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No. WO</th>
                    <th>No. GI</th>
                    <th>Part Name</th>
                    <th>Tanggal QC</th>
                    <th class="text-right" style="color:var(--success);">OK (Good)</th>
                    <th class="text-right" style="color:var(--danger);">Total NG</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($qcs as $qc)
                <tr>
                    <td>
                        <a href="{{ route('production-qc.show', $qc) }}" style="font-family:monospace;font-size:12px;color:var(--accent);font-weight:600;">
                            {{ $qc->wo_number }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('good-issues.show', $qc->goodIssue) }}" style="font-family:monospace;font-size:12px;color:var(--text-muted);">
                            {{ $qc->goodIssue->gi_number }}
                        </a>
                    </td>
                    <td style="font-size:13px;">{{ $qc->part->part_name ?? '-' }}</td>
                    <td style="font-size:12px;color:var(--text-muted);">{{ $qc->qc_date->format('d M Y') }}</td>
                    <td class="text-right" style="color:var(--success);font-weight:600;">
                        {{ number_format($qc->quantity_passed, 2) }}
                    </td>
                    <td class="text-right" style="color:var(--danger);font-weight:600;">
                        {{ number_format($qc->total_ng, 2) }}
                        @if($qc->quantity_failed_retur > 0)
                            <i class="fas fa-recycle" style="font-size:10px; color:var(--warning); margin-left:4px;" title="Terdapat NG Retur Material"></i>
                        @endif
                    </td>
                    <td>
                        @if($qc->status == 'approved')
                            <span class="badge badge-success">Disetujui</span>
                        @else
                            <span class="badge badge-warning">Draft</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div style="display:flex;gap:6px;justify-content:center;">
                            {{-- Tombol Detail --}}
                            <a href="{{ route('production-qc.show', $qc) }}" class="btn btn-xs btn-secondary" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                            {{-- Tombol Print --}}
                            <a href="{{ route('production-qc.print', $qc) }}" target="_blank" class="btn btn-xs" style="background-color: var(--accent); color: white;" title="Cetak/Print">
                                <i class="fas fa-print"></i>
                            </a>

                            {{-- Tombol Edit & Delete hanya muncul jika status masih DRAFT --}}
                            @if($qc->status === 'draft')
                            <a href="{{ route('production-qc.edit', $qc) }}" class="btn btn-xs btn-warning" title="Edit Data">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('production-qc.destroy', $qc) }}" method="POST" onsubmit="return confirm('Hapus data Work Order ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-danger" title="Hapus Data"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:40px;color:var(--text-dim);">
                        <i class="fas fa-clipboard-check" style="font-size:28px;margin-bottom:10px;display:block;"></i>
                        Belum ada data Work Order. Klik tombol <strong>Buat Work Order Baru</strong> di atas untuk memulai.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($qcs->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border);display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
        @if($qcs->onFirstPage())
            <span class="btn btn-xs btn-ghost" style="opacity:0.4;">« Prev</span>
        @else
            <a href="{{ $qcs->previousPageUrl() }}" class="btn btn-xs btn-secondary">« Prev</a>
        @endif
        @foreach($qcs->getUrlRange(1, $qcs->lastPage()) as $page => $url)
            @if($page == $qcs->currentPage())
                <span class="btn btn-xs btn-primary">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="btn btn-xs btn-secondary">{{ $page }}</a>
            @endif
        @endforeach
        @if($qcs->hasMorePages())
            <a href="{{ $qcs->nextPageUrl() }}" class="btn btn-xs btn-secondary">Next »</a>
        @else
            <span class="btn btn-xs btn-ghost" style="opacity:0.4;">Next »</span>
        @endif
    </div>
    @endif
</div>
@endsection