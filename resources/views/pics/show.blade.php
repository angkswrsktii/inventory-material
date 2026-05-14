@extends('layouts.app')
@section('title', 'Detail PIC')
@section('topbar-title', 'Detail PIC')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('pics.index') }}">Data PIC</a>
    <span class="sep">/</span>
    <span>{{ $pic->name }}</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">{{ $pic->name }}</div>
        <div class="page-subtitle">Detail informasi Person In Charge</div>
    </div>
    <div style="display:flex; gap:10px;">
        @if(auth()->user()?->isAdmin())
        <a href="{{ route('pics.edit', $pic) }}" class="btn btn-primary">
            <i class="fas fa-pen"></i> Edit
        </a>
        @endif
    </div>
</div>

<div style="display:grid; grid-template-columns: 400px 1fr; gap:20px; align-items:start;">
    <!-- Info Panel -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-user-tag" style="color:var(--accent);margin-right:8px;"></i>Informasi PIC</span>
        </div>
        <div style="padding:0;">
            @php
                $rows = [
                    ['Nama Lengkap',  $pic->name],
                    ['Posisi/Bagian', $pic->position ?: '-'],
                    ['Status',        $pic->is_active ? 'Aktif' : 'Nonaktif'],
                ];
            @endphp
            @foreach($rows as [$label, $value])
            <div style="display:flex; justify-content:space-between; align-items:center;
                        padding:11px 20px; border-bottom:1px solid var(--border); gap:12px;">
                <span style="font-size:12px; color:var(--text-muted); white-space:nowrap;">{{ $label }}</span>
                <span style="font-size:13px; color:var(--text); font-weight:500; text-align:right;">{{ $value }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
