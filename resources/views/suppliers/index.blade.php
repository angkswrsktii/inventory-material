@extends('layouts.app')
@section('title', 'Data Supplier')
@section('topbar-title', __('app.nav.master_data') . ' — ' . __('app.nav.data_supplier'))

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Data Supplier</div>
        <div class="page-subtitle">Kelola daftar supplier / vendor</div>
    </div>
    <a href="{{ route('suppliers.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Tambah Supplier
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
        <form method="GET" action="{{ route('suppliers.index') }}" style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
            <div style="flex:1; min-width:200px; position:relative;">
                <i class="fas fa-search" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:13px;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, telepon, email..."
                    style="width:100%; background:var(--surface-2); border:1px solid var(--border); color:var(--text); padding:8px 12px 8px 34px; border-radius:var(--radius-sm); font-family:inherit; font-size:13px; outline:none;">
            </div>
            <select name="status" style="background:var(--surface-2); border:1px solid var(--border); color:var(--text); padding:8px 12px; border-radius:var(--radius-sm); font-family:inherit; font-size:13px; outline:none;">
                <option value="">Semua Status</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Cari</button>
            @if(request('search') || request('status') !== null)
                <a href="{{ route('suppliers.index') }}" class="btn btn-ghost btn-sm"><i class="fas fa-times"></i> Reset</a>
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
                    <th width="90">Kode</th>
                    <th>Nama Supplier</th>
                    <th>Kontak Person</th>
                    <th>Telepon</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $supplier)
                <tr>
                    <td style="color:var(--text-muted);">{{ $suppliers->firstItem() + $loop->index }}</td>
                    <td>
                        <span style="font-family:monospace; font-size:12px; background:var(--surface-2); padding:2px 8px; border-radius:4px; border:1px solid var(--border);">
                            {{ $supplier->code ?: '—' }}
                        </span>
                    </td>
                    <td>
                        <div style="font-weight:600;">{{ $supplier->name }}</div>
                        @if($supplier->address)
                            <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">{{ Str::limit($supplier->address, 40) }}</div>
                        @endif
                    </td>
                    <td style="color:var(--text-muted);">{{ $supplier->contact_person ?: '—' }}</td>
                    <td style="color:var(--text-muted);">{{ $supplier->phone ?: '—' }}</td>
                    <td style="font-size:13px;color:var(--text-muted);">{{ $supplier->email ?: '—' }}</td>
                    <td>
                        @if($supplier->is_active)
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-danger">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex; gap:6px;">
                            <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-ghost btn-sm" title="Detail"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-ghost btn-sm" title="Edit"><i class="fas fa-pen"></i></a>
                            <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('Hapus supplier {{ $supplier->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-ghost btn-sm" title="Hapus" style="color:var(--danger);"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state" style="padding: 60px 20px;">
                            <i class="fas fa-building"></i>
                            <h4>Belum Ada Supplier</h4>
                            <p>Mulai tambah supplier pertama kamu</p>
                            <a href="{{ route('suppliers.create') }}" class="btn btn-primary btn-sm" style="margin-top:12px;"><i class="fas fa-plus"></i> Tambah Supplier</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($suppliers->hasPages())
    <div style="padding: 16px 20px; border-top: 1px solid var(--border); display:flex; justify-content:space-between; align-items:center;">
        <div style="font-size:13px; color:var(--text-muted);">
            Menampilkan {{ $suppliers->firstItem() }}–{{ $suppliers->lastItem() }} dari {{ $suppliers->total() }} supplier
        </div>
        <div style="display:flex; gap:6px;">
            {{ $suppliers->links() }}
        </div>
    </div>
    @endif
</div>
@endsection