@extends('layouts.app')
@section('title', 'Data PIC')
@section('topbar-title', 'Master PIC')

@section('content')
<div class="breadcrumb">
    <span>Data PIC</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">Data PIC</div>
        <div class="page-subtitle">Kelola daftar Person In Charge (Penerima/Pemotong Barang)</div>
    </div>
    @if(auth()->user()?->isAdmin())
    <div style="display:flex; gap:10px;">
        <a href="{{ route('pics.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah PIC
        </a>
    </div>
    @endif
</div>

<div class="card">
    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
        <span class="card-title">Daftar PIC</span>
        <form action="{{ route('pics.index') }}" method="GET" style="display:flex; gap:10px; align-items:center;">
            <select name="status" class="form-control" style="width:130px; font-size:13px;" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            <div style="position:relative;">
                <input type="text" name="search" class="form-control" placeholder="Cari nama/posisi..." value="{{ request('search') }}" style="width:200px; padding-left:32px; font-size:13px;">
                <i class="fas fa-search" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:12px;"></i>
            </div>
            @if(request('search') || request('status') !== null)
            <a href="{{ route('pics.index') }}" class="btn btn-ghost btn-sm" title="Clear Filter"><i class="fas fa-times"></i></a>
            @endif
        </form>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>Nama Lengkap</th>
                    <th>Posisi / Bagian</th>
                    <th>Status</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pics as $index => $pic)
                <tr>
                    <td style="color:var(--text-muted);">{{ $pics->firstItem() + $index }}</td>
                    <td style="font-weight:500;">{{ $pic->name }}</td>
                    <td>{{ $pic->position ?: '-' }}</td>
                    <td>
                        @if($pic->is_active)
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-danger">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex; gap:5px;">
                            <a href="{{ route('pics.show', $pic) }}" class="btn btn-ghost btn-sm" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if(auth()->user()?->isAdmin())
                            <a href="{{ route('pics.edit', $pic) }}" class="btn btn-ghost btn-sm" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form action="{{ route('pics.toggle-active', $pic) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin mengubah status PIC ini?');">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-ghost btn-sm" title="{{ $pic->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <i class="fas fa-power-off" style="color:{{ $pic->is_active ? 'var(--danger)' : 'var(--success)' }}"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="fas fa-user-tag"></i>
                            <h4>Belum Ada PIC</h4>
                            <p>Data PIC (Person In Charge) masih kosong atau tidak ditemukan.</p>
                            @if(auth()->user()?->isAdmin())
                            <a href="{{ route('pics.create') }}" class="btn btn-primary" style="margin-top:15px;">
                                <i class="fas fa-plus"></i> Tambah PIC Pertama
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($pics->hasPages())
    <div style="padding: 15px 20px; border-top: 1px solid var(--border);">
        {{ $pics->links() }}
    </div>
    @endif
</div>
@endsection
