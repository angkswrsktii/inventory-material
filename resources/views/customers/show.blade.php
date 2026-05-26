@extends('layouts.app')

@section('title', 'Detail Customer')
@section('topbar-title', __('app.nav.master_data') . ' — ' . __('app.nav.data_customer'))

@section('content')
<div class="breadcrumb">
    <a href="{{ route('customers.index') }}">Data Customer</a>
    <span class="sep">/</span>
    <span>{{ $customer->name }}</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">{{ $customer->name }}</div>
        <div class="page-subtitle">Detail informasi pelanggan</div>
    </div>
    <div style="display:flex; gap:10px;">
        @if(auth()->user()?->isAdmin())
        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-primary">
            <i class="fas fa-pen"></i> Edit
        </a>
        @endif
    </div>
</div>

<div style="display:grid; grid-template-columns: 350px 1fr; gap:20px; align-items:start;">
    <!-- Info Panel -->
    <div style="display:flex; flex-direction:column; gap:20px;">
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-user-tie" style="color:var(--accent);margin-right:8px;"></i>Informasi Pelanggan</span>
            </div>
            <div style="padding:0;">
                @php
                    $rows = [
                        ['Nama Customer',   $customer->name],
                        ['Kontak Person',   $customer->contact_person ?: '-'],
                        ['Telepon',         $customer->phone ?: '-'],
                        ['Email',           $customer->email ?: '-'],
                        ['Status',          $customer->is_active ? 'Aktif' : 'Nonaktif'],
                    ];
                @endphp
                @foreach($rows as [$label, $value])
                <div style="display:flex; justify-content:space-between; align-items:center;
                            padding:11px 20px; border-bottom:1px solid var(--border); gap:12px;">
                    <span style="font-size:12px; color:var(--text-muted); white-space:nowrap;">{{ $label }}</span>
                    <span style="font-size:13px; color:var(--text); font-weight:500; text-align:right;">{{ $value }}</span>
                </div>
                @endforeach
                <div style="padding:12px 20px;">
                    <div style="font-size:12px; color:var(--text-muted); margin-bottom:5px;">Alamat</div>
                    <div style="font-size:13px; color:var(--text);">{{ $customer->address ?: '-' }}</div>
                </div>
            </div>
        </div>

        </div>
    </div>

    <!-- Related Parts -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-cubes" style="color:var(--accent-2);margin-right:8px;"></i>Daftar Part</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Part No.</th>
                        <th>Part Name</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customer->parts as $part)
                    <tr>
                        <td>
                            <a href="{{ route('parts.show', $part) }}" class="mono" style="color:var(--text); text-decoration:none; font-weight:600;">
                                {{ $part->part_no }}
                            </a>
                        </td>
                        <td>{{ $part->part_name }}</td>
                        <td>
                            @if($part->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-danger">Non-Aktif</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3">
                            <div class="empty-state" style="padding:40px;">
                                <i class="fas fa-inbox"></i>
                                <h4>Belum Ada Part</h4>
                                <p>Customer ini belum memiliki daftar part.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
