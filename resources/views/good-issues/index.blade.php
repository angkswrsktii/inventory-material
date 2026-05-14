@extends('layouts.app')

@section('title', 'Good Issue (GI)')
@section('topbar-title', 'Good Issue (GI)')

@section('topbar-actions')
    <span style="font-size:12px; color: var(--text-muted);">
        <i class="fas fa-clock"></i>
        {{ now()->format('d M Y, H:i') }}
    </span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Good Issue</div>
        <div class="page-subtitle">Pengeluaran barang dari gudang untuk produksi atau keperluan lain</div>
    </div>
    <a href="{{ route('good-issues.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Keluarkan Barang
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

<!-- Filter -->
<div class="card" style="margin-bottom: 20px;">
    <div class="card-body" style="padding: 16px 20px;">
        <form method="GET" action="{{ route('good-issues.index') }}" style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
            <div style="flex:1; min-width:200px; position:relative;">
                <i class="fas fa-search" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:13px;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. GI, Tujuan / Catatan..."
                    style="width:100%; background:var(--surface-2); border:1px solid var(--border); color:var(--text); padding:8px 12px 8px 34px; border-radius:var(--radius-sm); font-family:inherit; font-size:13px; outline:none;">
            </div>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Cari</button>
            @if(request('search'))
                <a href="{{ route('good-issues.index') }}" class="btn btn-ghost btn-sm"><i class="fas fa-times"></i> Reset</a>
            @endif
        </form>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th>No. GI</th>
                    <th>Tanggal Keluar</th>
                    <th>Target Part</th>
                    <th>PIC Pemotong</th>
                    <th>Tujuan / Catatan</th>
                    <th width="90">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($goodIssues as $gi)
                <tr>
                    <td style="color:var(--text-muted);">{{ $goodIssues->firstItem() + $loop->index }}</td>
                    <td>
                        <span style="font-family:'Syne',sans-serif; font-size:12px; font-weight:600; color:var(--danger); background:rgba(248,113,113,0.1); padding:3px 8px; border-radius:4px; white-space:nowrap;">
                            {{ $gi->gi_number }}
                        </span>
                    </td>
                    <td style="color:var(--text-muted);">{{ $gi->issue_date->format('d M Y') }}</td>
                    <td>{{ $gi->part->part_name ?? '-' }}</td>
                    <td>{{ $gi->pic->name ?? '-' }}</td>
                    <td>
                        @if($gi->purpose)
                            <div style="font-weight:500; font-size:13px;">{{ Str::limit($gi->purpose, 40) }}</div>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <div style="display:flex; gap:6px;">
                            <a href="{{ route('good-issues.show', $gi) }}" class="btn btn-ghost btn-sm" title="Detail"><i class="fas fa-eye"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state" style="padding: 60px 20px;">
                            <i class="fas fa-box-open"></i>
                            <h4>Belum Ada Pengeluaran Barang</h4>
                            <p>Mulai catat pengeluaran barang untuk produksi atau keperluan lainnya</p>
                            <a href="{{ route('good-issues.create') }}" class="btn btn-primary btn-sm" style="margin-top:12px;"><i class="fas fa-plus"></i> Keluarkan Barang</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($goodIssues->hasPages())
    <div style="padding: 16px 20px; border-top: 1px solid var(--border); display:flex; justify-content:space-between; align-items:center;">
        <div style="font-size:13px; color:var(--text-muted);">
            Menampilkan {{ $goodIssues->firstItem() }}–{{ $goodIssues->lastItem() }} dari {{ $goodIssues->total() }} GI
        </div>
        <div style="display:flex; gap:6px;">
            {{ $goodIssues->links() }}
        </div>
    </div>
    @endif
</div>
@endsection