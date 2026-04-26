@extends('layouts.app')

@section('title', 'Data Material')
@section('topbar-title', 'Data Material')

@section('topbar-actions')
    <span style="font-size:12px; color: var(--text-muted);">
        <i class="fas fa-clock"></i>
        {{ now()->format('d M Y, H:i') }}
    </span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Data Material</div>
        <div class="page-subtitle">Manajemen master data raw material</div>
    </div>
     <a href="{{ route('materials.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Tambah Material
    </a>
</div>

<!-- Stats -->
<div class="stats-grid" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 24px;">
    <div class="stat-card blue">
        <div class="stat-icon"><i class="fas fa-cube"></i></div>
        <div class="stat-value">{{ $stats['total'] }}</div>
        <div class="stat-label">Total Material</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
        <div class="stat-value">{{ $stats['normal'] }}</div>
        <div class="stat-label">Stok Normal</div>
    </div>
    <div class="stat-card yellow">
        <div class="stat-icon"><i class="fas fa-triangle-exclamation"></i></div>
        <div class="stat-value">{{ $stats['low'] }}</div>
        <div class="stat-label">Stok Rendah</div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon"><i class="fas fa-ban"></i></div>
        <div class="stat-value">{{ $stats['empty'] }}</div>
        <div class="stat-label">Stok Kosong</div>
    </div>
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
        <form method="GET" action="{{ route('materials.index') }}" style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
            <div style="flex:1; min-width:200px; position:relative;">
                <i class="fas fa-search" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:13px;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, kode, supplier..."
                    style="width:100%; background:var(--surface-2); border:1px solid var(--border); color:var(--text); padding:8px 12px 8px 34px; border-radius:var(--radius-sm); font-family:inherit; font-size:13px; outline:none;">
            </div>
            <select name="status" style="background:var(--surface-2); border:1px solid var(--border); color:var(--text); padding:8px 12px; border-radius:var(--radius-sm); font-family:inherit; font-size:13px; outline:none;">
                <option value="">Semua Status</option>
                <option value="normal" {{ request('status') == 'normal' ? 'selected' : '' }}>Normal</option>
                <option value="low" {{ request('status') == 'low' ? 'selected' : '' }}>Stok Rendah</option>
                <option value="empty" {{ request('status') == 'empty' ? 'selected' : '' }}>Stok Kosong</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Cari</button>
            @if(request('search') || request('status'))
                <a href="{{ route('materials.index') }}" class="btn btn-ghost btn-sm"><i class="fas fa-times"></i> Reset</a>
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
                    <th>Kode</th>
                    <th>Nama Material</th>
                    <th>Spesifikasi</th>
                    <th>Supplier</th>
                    <th class="text-right">Min. Stok</th>
                    <th class="text-right">Stok Saat Ini</th>
                    <th>Status</th>
                    <th width="110">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($materials as $material)
                <tr>
                    <td style="color:var(--text-muted);">{{ $materials->firstItem() + $loop->index }}</td>
                    <td>
                        <span style="font-family:'Syne',sans-serif; font-size:12px; font-weight:600; color:var(--accent); background:var(--accent-glow); padding:3px 8px; border-radius:4px; white-space:nowrap;">
                            {{ $material->code }}
                        </span>
                    </td>
                    <td>
                        <div style="font-weight:500;">{{ $material->name }}</div>
                        @if($material->description)
                            <div style="font-size:11px; color:var(--text-muted);">{{ Str::limit($material->description, 40) }}</div>
                        @endif
                    </td>
                    <td style="color:var(--text-muted); font-size:13px;">{{ $material->specification ?? '-' }}</td>
                    <td style="color:var(--text-muted); font-size:13px;">{{ $material->supplier ?? '-' }}</td>
                    <td class="text-right" style="color:var(--text-muted);">{{ number_format($material->minimum_stock, 2) }} {{ $material->unit }}</td>
                    <td class="text-right" style="font-weight:600; color: {{ $material->current_stock <= 0 ? 'var(--danger)' : ($material->current_stock <= $material->minimum_stock ? 'var(--warning)' : 'var(--success)') }}">
                        {{ number_format($material->current_stock, 2) }} {{ $material->unit }}
                    </td>
                    <td>
                        @if($material->current_stock <= 0)
                            <span class="badge badge-danger"><i class="fas fa-ban fa-xs"></i> Kosong</span>
                        @elseif($material->current_stock <= $material->minimum_stock)
                            <span class="badge badge-warning"><i class="fas fa-triangle-exclamation fa-xs"></i> Rendah</span>
                        @else
                            <span class="badge badge-success"><i class="fas fa-check fa-xs"></i> Normal</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex; gap:6px;">
                            <a href="{{ route('materials.show', $material) }}" class="btn btn-ghost btn-sm" title="Detail"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('materials.edit', $material) }}" class="btn btn-ghost btn-sm" title="Edit"><i class="fas fa-pen"></i></a>
                            <form method="POST" action="{{ route('materials.destroy', $material) }}" onsubmit="return confirm('Yakin hapus material ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-ghost btn-sm" title="Hapus" style="color:var(--danger);"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-state" style="padding: 60px 20px;">
                            <i class="fas fa-cube"></i>
                            <h4>Belum Ada Material</h4>
                            <p>Mulai tambah material pertama kamu</p>
                            <a href="{{ route('materials.create') }}" class="btn btn-primary btn-sm" style="margin-top:12px;"><i class="fas fa-plus"></i> Tambah Material</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($materials->hasPages())
    <div style="padding: 16px 20px; border-top: 1px solid var(--border); display:flex; justify-content:space-between; align-items:center;">
        <div style="font-size:13px; color:var(--text-muted);">
            Menampilkan {{ $materials->firstItem() }}–{{ $materials->lastItem() }} dari {{ $materials->total() }} material
        </div>
        <div style="display:flex; gap:6px;">
            @if($materials->onFirstPage())
                <span class="btn btn-ghost btn-sm" style="opacity:0.4; cursor:not-allowed;"><i class="fas fa-chevron-left"></i></span>
            @else
                <a href="{{ $materials->previousPageUrl() }}" class="btn btn-ghost btn-sm"><i class="fas fa-chevron-left"></i></a>
            @endif
            @foreach($materials->getUrlRange(max(1, $materials->currentPage()-2), min($materials->lastPage(), $materials->currentPage()+2)) as $page => $url)
                <a href="{{ $url }}" class="btn btn-sm {{ $page == $materials->currentPage() ? 'btn-primary' : 'btn-ghost' }}">{{ $page }}</a>
            @endforeach
            @if($materials->hasMorePages())
                <a href="{{ $materials->nextPageUrl() }}" class="btn btn-ghost btn-sm"><i class="fas fa-chevron-right"></i></a>
            @else
                <span class="btn btn-ghost btn-sm" style="opacity:0.4; cursor:not-allowed;"><i class="fas fa-chevron-right"></i></span>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection