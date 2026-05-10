@extends('layouts.app')
@section('title', 'Data Customer')
@section('topbar-title', 'Master Data')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Data Customer</div>
        <div class="page-subtitle">Kelola daftar customer / pelanggan</div>
    </div>
    <a href="{{ route('customers.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Customer
    </a>
</div>

@if(session('success'))
<div class="alert alert-success" style="margin-bottom:16px;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="alert alert-danger" style="margin-bottom:16px;">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
</div>
@endif

<div class="card">
    <div class="card-header" style="gap:12px;flex-wrap:wrap;">
        <form method="GET" style="display:flex;gap:10px;flex:1;flex-wrap:wrap;">
            <div style="position:relative;flex:1;min-width:200px;">
                <i class="fas fa-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-dim);font-size:13px;"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="form-control" style="padding-left:36px;"
                       placeholder="Cari nama, telepon, email...">
            </div>
            <select name="status" class="form-control" style="width:150px;">
                <option value="">Semua Status</option>
                <option value="1" {{ request('status')==='1' ? 'selected':'' }}>Aktif</option>
                <option value="0" {{ request('status')==='0' ? 'selected':'' }}>Nonaktif</option>
            </select>
            <button type="submit" class="btn btn-secondary"><i class="fas fa-filter"></i> Filter</button>
            @if(request('search') || request('status') !== null)
                <a href="{{ route('customers.index') }}" class="btn btn-ghost">Reset</a>
            @endif
        </form>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama Customer</th>
                    <th>Kontak Person</th>
                    <th>Telepon</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Ditambahkan</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr>
                    <td>
                        <div style="font-weight:600;">{{ $customer->name }}</div>
                        @if($customer->address)
                            <div style="font-size:11px;color:var(--text-dim);margin-top:2px;">{{ Str::limit($customer->address, 40) }}</div>
                        @endif
                    </td>
                    <td style="color:var(--text-muted);">{{ $customer->contact_person ?: '—' }}</td>
                    <td style="color:var(--text-muted);">{{ $customer->phone ?: '—' }}</td>
                    <td style="font-size:12px;color:var(--text-muted);">{{ $customer->email ?: '—' }}</td>
                    <td>
                        @if($customer->is_active)
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-muted">Nonaktif</span>
                        @endif
                    </td>
                    <td style="font-size:12px;color:var(--text-muted);">
                        {{ $customer->created_at->format('d M Y') }}<br>
                        <span style="font-size:11px;">{{ $customer->creator?->name ?? '—' }}</span>
                    </td>
                    <td class="text-center">
                        <div style="display:flex;gap:6px;justify-content:center;">
                            <a href="{{ route('customers.show', $customer) }}" class="btn btn-xs btn-secondary" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-xs btn-secondary" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form action="{{ route('customers.toggle-active', $customer) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-xs {{ $customer->is_active ? 'btn-warning' : 'btn-success' }}"
                                        title="{{ $customer->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <i class="fas fa-{{ $customer->is_active ? 'ban' : 'check' }}"></i>
                                </button>
                            </form>
                            <form action="{{ route('customers.destroy', $customer) }}" method="POST"
                                  onsubmit="return confirm('Hapus customer {{ $customer->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-danger" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:32px;color:var(--text-dim);">
                        <i class="fas fa-users" style="font-size:24px;margin-bottom:8px;display:block;"></i>
                        Belum ada data customer.
                        <a href="{{ route('customers.create') }}" style="color:var(--accent);">Tambah sekarang</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($customers->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border);display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
        @if($customers->onFirstPage())
            <span class="btn btn-xs btn-ghost" style="opacity:0.4;">« Prev</span>
        @else
            <a href="{{ $customers->previousPageUrl() }}" class="btn btn-xs btn-secondary">« Prev</a>
        @endif

        @foreach($customers->getUrlRange(1, $customers->lastPage()) as $page => $url)
            @if($page == $customers->currentPage())
                <span class="btn btn-xs btn-primary">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="btn btn-xs btn-secondary">{{ $page }}</a>
            @endif
        @endforeach

        @if($customers->hasMorePages())
            <a href="{{ $customers->nextPageUrl() }}" class="btn btn-xs btn-secondary">Next »</a>
        @else
            <span class="btn btn-xs btn-ghost" style="opacity:0.4;">Next »</span>
        @endif

        <span style="font-size:12px;color:var(--text-dim);margin-left:8px;">
            Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }} results
        </span>
    </div>
    @endif
</div>
@endsection
