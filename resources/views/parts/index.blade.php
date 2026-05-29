@extends('layouts.app')

@section('title', 'Data Part')
@section('topbar-title', __('app.nav.master_data') . ' — ' . __('app.nav.data_part'))

@section('topbar-actions')
    <span style="font-size:12px; color: var(--text-muted);">
        <i class="fas fa-clock"></i>
        {{ now()->format('d M Y, H:i') }}
    </span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">{{ __('app.part.title') }}</div>
        <div class="page-subtitle">{{ __('app.part.subtitle') }}</div>
    </div>
     <a href="{{ route('parts.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> {{ __("app.part.add") }}
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
        <form method="GET" action="{{ route('parts.index') }}" style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
            <div style="flex:1; min-width:200px; position:relative;">
                <i class="fas fa-search" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:13px;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Part No, Part Name, Customer..."
                    style="width:100%; background:var(--surface-2); border:1px solid var(--border); color:var(--text); padding:8px 12px 8px 34px; border-radius:var(--radius-sm); font-family:inherit; font-size:13px; outline:none;">
            </div>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> {{ __('app.btn.search') }}</button>
            @if(request('search'))
                <a href="{{ route('parts.index') }}" class="btn btn-ghost btn-sm"><i class="fas fa-times"></i> {{ __('app.btn.reset') }}</a>
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
                    <th width="50">{{ __('app.common.no') }}</th>
                    <th>Part No.</th>
                    <th>Part Name</th>
                    <th>Customer</th>
                    <th>Panjang Part</th>
                    <th>B/Q</th>
                    <th>{{ __('app.common.status') }}</th>
                    <th width="110">{{ __('app.common.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($parts as $part)
                <tr>
                    <td style="color:var(--text-muted);">{{ $parts->firstItem() + $loop->index }}</td>
                    <td>
                        <span style="font-family:'Syne',sans-serif; font-size:12px; font-weight:600; color:var(--accent); background:var(--accent-glow); padding:3px 8px; border-radius:4px; white-space:nowrap;">
                            {{ $part->part_no }}
                        </span>
                    </td>
                    <td>
                        <div style="font-weight:500;">{{ $part->part_name }}</div>
                        @if($part->description)
                            <div style="font-size:11px; color:var(--text-muted);">{{ Str::limit($part->description, 40) }}</div>
                        @endif
                    </td>
                    <td style="color:var(--text-muted); font-size:13px;">{{ $part->customer->name ?? '-' }}</td>
                    <td style="color:var(--text-muted); font-size:13px;">
                        {{ $part->panjang_part ? number_format($part->panjang_part, 2).' mm' : '-' }}
                    </td>
                    <td style="color:var(--text-muted); font-size:13px;">
                        {{ $part->bq ? number_format($part->bq, 4) : '-' }}
                    </td>
                    <td>
                        @if($part->is_active)
                            <span class="badge badge-success"><i class="fas fa-check fa-xs"></i> Aktif</span>
                        @else
                            <span class="badge badge-danger"><i class="fas fa-times fa-xs"></i> Non-Aktif</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex; gap:6px;">
                            <a href="{{ route('parts.show', $part) }}" class="btn btn-ghost btn-sm" title="Detail"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('parts.edit', $part) }}" class="btn btn-ghost btn-sm" title="Edit"><i class="fas fa-pen"></i></a>
                            <form method="POST" action="{{ route('parts.destroy', $part) }}" data-confirm="{{ __('app.common.confirm_delete') }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-ghost btn-sm" title="{{ __('app.btn.delete') }}" style="color:var(--danger);"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state" style="padding: 60px 20px;">
                            <i class="fas fa-cubes"></i>
                            <h4>{{ __("app.part.empty_title") }}</h4>
                            <p>Mulai tambah part pertama kamu</p>
                            <a href="{{ route('parts.create') }}" class="btn btn-primary btn-sm" style="margin-top:12px;"><i class="fas fa-plus"></i> {{ __("app.part.add") }}</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($parts->hasPages())
    <div style="padding: 16px 20px; border-top: 1px solid var(--border); display:flex; justify-content:space-between; align-items:center;">
        <div style="font-size:13px; color:var(--text-muted);">
            Menampilkan {{ $parts->firstItem() }}–{{ $parts->lastItem() }} dari {{ $parts->total() }} part
        </div>
        <div style="display:flex; gap:6px;">
            @if($parts->onFirstPage())
                <span class="btn btn-ghost btn-sm" style="opacity:0.4; cursor:not-allowed;"><i class="fas fa-chevron-left"></i></span>
            @else
                <a href="{{ $parts->previousPageUrl() }}" class="btn btn-ghost btn-sm"><i class="fas fa-chevron-left"></i></a>
            @endif
            @foreach($parts->getUrlRange(max(1, $parts->currentPage()-2), min($parts->lastPage(), $parts->currentPage()+2)) as $page => $url)
                <a href="{{ $url }}" class="btn btn-sm {{ $page == $parts->currentPage() ? 'btn-primary' : 'btn-ghost' }}">{{ $page }}</a>
            @endforeach
            @if($parts->hasMorePages())
                <a href="{{ $parts->nextPageUrl() }}" class="btn btn-ghost btn-sm"><i class="fas fa-chevron-right"></i></a>
            @else
                <span class="btn btn-ghost btn-sm" style="opacity:0.4; cursor:not-allowed;"><i class="fas fa-chevron-right"></i></span>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
