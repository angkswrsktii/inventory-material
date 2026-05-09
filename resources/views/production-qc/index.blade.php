@extends('layouts.app')
@section('title', 'Quality Control Hasil Produksi')
@section('topbar-title', 'Quality Control Hasil Produksi')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Quality Control Hasil Produksi</div>
        <div class="page-subtitle">Quality control barang hasil produksi dari kartu pengambilan</div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success" style="margin-bottom:16px;"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:20px;">
    <div class="card" style="padding:20px;">
        <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;letter-spacing:.5px;">Draft</div>
        <div style="font-size:28px;font-weight:800;font-family:'Syne',sans-serif;color:var(--warning);">{{ $stats['draft'] }}</div>
    </div>
    <div class="card" style="padding:20px;">
        <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;letter-spacing:.5px;">Disetujui</div>
        <div style="font-size:28px;font-weight:800;font-family:'Syne',sans-serif;color:var(--success);">{{ $stats['approved'] }}</div>
    </div>
    <div class="card" style="padding:20px;">
        <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;letter-spacing:.5px;">Ditolak</div>
        <div style="font-size:28px;font-weight:800;font-family:'Syne',sans-serif;color:var(--danger);">{{ $stats['rejected'] }}</div>
    </div>
    <div class="card" style="padding:20px;">
        <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;letter-spacing:.5px;">Total NG</div>
        <div style="font-size:28px;font-weight:800;font-family:'Syne',sans-serif;color:var(--danger);">{{ number_format($stats['total_ng'],0) }}</div>
    </div>
</div>

<div class="card">
    <div class="card-header" style="gap:12px;flex-wrap:wrap;">
        <form method="GET" style="display:flex;gap:10px;flex:1;flex-wrap:wrap;">
            <div style="position:relative;flex:1;min-width:200px;">
                <i class="fas fa-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-dim);font-size:13px;"></i>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                       style="padding-left:36px;" placeholder="Cari no. QC, no. WD, part name...">
            </div>
            <select name="status" class="form-control" style="width:150px;">
                <option value="">Semua Status</option>
                <option value="draft"    {{ request('status')==='draft'    ? 'selected':'' }}>Draft</option>
                <option value="approved" {{ request('status')==='approved' ? 'selected':'' }}>Disetujui</option>
                <option value="rejected" {{ request('status')==='rejected' ? 'selected':'' }}>Ditolak</option>
            </select>
            <select name="gedung" class="form-control" style="width:130px;">
                <option value="">Semua Gedung</option>
                <option value="Gedung 1" {{ request('gedung')==='Gedung 1' ? 'selected':'' }}>Gedung 1</option>
                <option value="Gedung 2" {{ request('gedung')==='Gedung 2' ? 'selected':'' }}>Gedung 2</option>
                <option value="Gedung 3" {{ request('gedung')==='Gedung 3' ? 'selected':'' }}>Gedung 3</option>
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control" style="width:150px;">
            <input type="date" name="date_to"   value="{{ request('date_to') }}"   class="form-control" style="width:150px;">
            <button type="submit" class="btn btn-secondary"><i class="fas fa-filter"></i> Filter</button>
            @if(request()->hasAny(['search','status','gedung','date_from','date_to']))
                <a href="{{ route('production-qc.index') }}" class="btn btn-ghost">Reset</a>
            @endif
        </form>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No. QC</th>
                    <th>No. WD</th>
                    <th>Part Name</th>
                    <th>Tanggal</th>
                    <th>Gedung</th>
                    <th class="text-right">Produksi</th>
                    <th class="text-right" style="color:var(--success);">SFG</th>
                    <th class="text-right" style="color:var(--danger);">NG</th>
                    <th class="text-right">% NG</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($qcs as $qc)
                <tr>
                    <td><a href="{{ route('production-qc.show', $qc) }}"
                           style="font-family:monospace;font-size:12px;color:var(--accent);">{{ $qc->document_no }}</a></td>
                    <td><a href="{{ route('withdrawal-cards.show', $qc->withdrawalCard) }}"
                           style="font-family:monospace;font-size:12px;color:var(--text-muted);">{{ $qc->withdrawalCard->document_no }}</a></td>
                    <td style="font-size:13px;">{{ $qc->withdrawalCard->part_name }}</td>
                    <td style="font-size:12px;color:var(--text-muted);">{{ $qc->qc_date->format('d M Y') }}</td>
                    <td style="font-size:12px;">{{ $qc->gedung ?: '—' }}</td>
                    <td class="text-right" style="font-weight:600;">{{ number_format($qc->qty_produksi,0) }}</td>
                    <td class="text-right" style="color:var(--success);font-weight:600;">{{ number_format($qc->qty_sfg,0) }}</td>
                    <td class="text-right" style="color:var(--danger);font-weight:600;">{{ number_format($qc->qty_ng,0) }}</td>
                    <td class="text-right">
                        <span style="font-size:12px;color:{{ $qc->ng_percentage > 5 ? 'var(--danger)' : 'var(--text-muted)' }};">
                            {{ $qc->ng_percentage }}%
                        </span>
                    </td>
                    <td><span class="badge badge-{{ $qc->status_color }}">{{ $qc->status_label }}</span></td>
                    <td class="text-center">
                        <div style="display:flex;gap:6px;justify-content:center;">
                            <a href="{{ route('production-qc.show', $qc) }}" class="btn btn-xs btn-secondary" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($qc->status === 'draft')
                            <form action="{{ route('production-qc.destroy', $qc) }}" method="POST"
                                  onsubmit="return confirm('Hapus data Quality Control ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" style="text-align:center;padding:40px;color:var(--text-dim);">
                        <i class="fas fa-microscope" style="font-size:28px;margin-bottom:10px;display:block;"></i>
                        Belum ada data Quality Control. Input Quality Control dari halaman <a href="{{ route('withdrawal-cards.index') }}" style="color:var(--accent);">Kartu Pengambilan</a>.
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