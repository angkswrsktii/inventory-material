@extends('layouts.app')
@section('title', 'Gudang')
@section('topbar-title', __('app.nav.master_data') . ' — ' . __('app.nav.data_warehouse'))

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Gudang</div>
        <div class="page-subtitle">Kelola daftar gudang penyimpanan material</div>
    </div>
    <a href="{{ route('warehouses.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Tambah Gudang
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
        <form method="GET" action="{{ route('warehouses.index') }}" style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
            <div style="flex:1; min-width:200px; position:relative;">
                <i class="fas fa-search" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:13px;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode, nama, lokasi..."
                    style="width:100%; background:var(--surface-2); border:1px solid var(--border); color:var(--text); padding:8px 12px 8px 34px; border-radius:var(--radius-sm); font-family:inherit; font-size:13px; outline:none;">
            </div>
            <select name="status" style="background:var(--surface-2); border:1px solid var(--border); color:var(--text); padding:8px 12px; border-radius:var(--radius-sm); font-family:inherit; font-size:13px; outline:none;">
                <option value="">Semua Status</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Cari</button>
            @if(request('search') || request('status') !== null)
                <a href="{{ route('warehouses.index') }}" class="btn btn-ghost btn-sm"><i class="fas fa-times"></i> Reset</a>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th width="100">Kode</th>
                    <th>Nama Gudang</th>
                    <th>Lokasi</th>
                    <th width="80">Stok</th>
                    <th width="90">Status</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($warehouses as $warehouse)
                <tr>
                    <td style="color:var(--text-muted);">{{ $warehouses->firstItem() + $loop->index }}</td>
                    <td>
                        <span style="font-family:monospace; font-size:12px; background:var(--surface-2); padding:2px 8px; border-radius:4px; border:1px solid var(--border);">
                            {{ $warehouse->code }}
                        </span>
                    </td>
                    <td>
                        <div style="font-weight:600;">{{ $warehouse->name }}</div>
                    </td>
                    <td style="color:var(--text-muted); font-size:13px;">{{ $warehouse->location ?: '—' }}</td>
                    <td style="color:var(--text-muted); text-align:center;">
                        {{ $warehouse->stocks_count ?? $warehouse->stocks()->count() }}
                    </td>
                    <td>
                        @if($warehouse->is_active)
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-danger">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex; gap:6px;">
                            <a href="{{ route('warehouses.show', $warehouse) }}" class="btn btn-ghost btn-sm" title="Detail"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('warehouses.edit', $warehouse) }}" class="btn btn-ghost btn-sm" title="Edit"><i class="fas fa-pen"></i></a>
                            <form action="{{ route('warehouses.toggle-active', $warehouse) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-ghost btn-sm" title="{{ $warehouse->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                    style="color:{{ $warehouse->is_active ? 'var(--warning)' : 'var(--success)' }};">
                                    <i class="fas fa-{{ $warehouse->is_active ? 'ban' : 'check' }}"></i>
                                </button>
                            </form>
                            <form action="{{ route('warehouses.destroy', $warehouse) }}" method="POST"
                                onsubmit="return confirm('Hapus gudang {{ $warehouse->name }}? Data stok yang terhubung tidak akan ikut terhapus.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-ghost btn-sm" title="Hapus" style="color:var(--danger);"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state" style="padding: 60px 20px;">
                            <i class="fas fa-warehouse"></i>
                            <h4>Belum Ada Gudang</h4>
                            <p>Mulai tambah gudang pertama kamu</p>
                            <a href="{{ route('warehouses.create') }}" class="btn btn-primary btn-sm" style="margin-top:12px;"><i class="fas fa-plus"></i> Tambah Gudang</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($warehouses->hasPages())
    <div style="padding: 16px 20px; border-top: 1px solid var(--border); display:flex; justify-content:space-between; align-items:center;">
        <div style="font-size:13px; color:var(--text-muted);">
            Menampilkan {{ $warehouses->firstItem() }}–{{ $warehouses->lastItem() }} dari {{ $warehouses->total() }} gudang
        </div>
        <div style="display:flex; gap:6px;">
            {{ $warehouses->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
