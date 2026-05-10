@extends('layouts.app')
@section('title', 'Detail Customer')
@section('topbar-title', 'Master Data')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('customers.index') }}">Data Customer</a>
    <span class="sep">/</span>
    <span>{{ $customer->name }}</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">{{ $customer->name }}</div>
        <div class="page-subtitle">Detail informasi customer</div>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-secondary">
            <i class="fas fa-pen"></i> Edit
        </a>
        <a href="{{ route('customers.index') }}" class="btn btn-ghost">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div style="max-width:680px;">
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-user" style="color:var(--accent);margin-right:8px;"></i>Informasi Customer</span>
            @if($customer->is_active)
                <span class="badge badge-success">Aktif</span>
            @else
                <span class="badge badge-muted">Nonaktif</span>
            @endif
        </div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                <div>
                    <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;margin-bottom:4px;">Nama Customer</div>
                    <div style="font-weight:600;">{{ $customer->name }}</div>
                </div>
                <div>
                    <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;margin-bottom:4px;">Kontak Person</div>
                    <div>{{ $customer->contact_person ?: '—' }}</div>
                </div>
                <div>
                    <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;margin-bottom:4px;">Telepon</div>
                    <div>{{ $customer->phone ?: '—' }}</div>
                </div>
                <div>
                    <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;margin-bottom:4px;">Email</div>
                    <div style="font-size:13px;">{{ $customer->email ?: '—' }}</div>
                </div>
                <div style="grid-column:span 2;">
                    <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;margin-bottom:4px;">Alamat</div>
                    <div>{{ $customer->address ?: '—' }}</div>
                </div>
                <div>
                    <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;margin-bottom:4px;">NPWP</div>
                    <div>{{ $customer->npwp ?: '—' }}</div>
                </div>
                <div>
                    <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;margin-bottom:4px;">Ditambahkan oleh</div>
                    <div>{{ $customer->creator?->name ?? '—' }}</div>
                    <div style="font-size:11px;color:var(--text-dim);">{{ $customer->created_at->format('d M Y') }}</div>
                </div>
                @if($customer->notes)
                <div style="grid-column:span 2;">
                    <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;margin-bottom:4px;">Catatan</div>
                    <div style="font-size:13px;color:var(--text-muted);">{{ $customer->notes }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div style="margin-top:16px;display:flex;gap:10px;">
        <form action="{{ route('customers.toggle-active', $customer) }}" method="POST">
            @csrf @method('PATCH')
            <button type="submit" class="btn {{ $customer->is_active ? 'btn-warning' : 'btn-success' }}">
                <i class="fas fa-{{ $customer->is_active ? 'ban' : 'check' }}"></i>
                {{ $customer->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
            </button>
        </form>
        <form action="{{ route('customers.destroy', $customer) }}" method="POST"
              onsubmit="return confirm('Hapus customer {{ $customer->name }}?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash"></i> Hapus
            </button>
        </form>
    </div>
</div>
@endsection
