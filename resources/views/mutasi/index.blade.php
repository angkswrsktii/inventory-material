@extends('layouts.app')
@section('title', 'Mutasi Stok')
@section('topbar-title', __('app.nav.inventory') . ' — ' . __('app.nav.mutation_history'))

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">{{ __('app.mutasi.title') }}</div>
        <div class="page-subtitle">{{ __('app.mutasi.subtitle') }}</div>
    </div>
</div>

<div class="card" style="margin-bottom: 20px;">
    <div class="card-body" style="padding: 16px 20px;">
        <form method="GET" action="{{ route('mutasi.index') }}" style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
            <div style="flex:1; min-width:200px; position:relative;">
                <i class="fas fa-search" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:13px;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('app.mutasi.search_placeholder') }}"
                    style="width:100%; background:var(--surface-2); border:1px solid var(--border); color:var(--text); padding:8px 12px 8px 34px; border-radius:var(--radius-sm); font-family:inherit; font-size:13px; outline:none;">
            </div>
            <select name="type" class="form-control" style="width:150px;">
                <option value="">{{ __('app.mutasi.all_types') }}</option>
                <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>{{ __('app.mutasi.in') }}</option>
                <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>{{ __('app.mutasi.out') }}</option>
                <option value="in_return" {{ request('type') == 'in_return' ? 'selected' : '' }}>{{ __('app.mutasi.in_return') }}</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> {{ __('app.btn.search') }}</button>
            @if(request()->hasAny(['search', 'type']))
                <a href="{{ route('mutasi.index') }}" class="btn btn-ghost btn-sm"><i class="fas fa-times"></i> {{ __('app.btn.reset') }}</a>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th width="50">{{ __('app.common.no') }}</th>
                    <th>{{ __('app.mutasi.time') }}</th>
                    <th>{{ __('app.mutasi.item') }}</th>
                    <th>{{ __('app.common.warehouse') }}</th>
                    <th>{{ __('app.mutasi.doc_ref') }}</th>
                    <th>{{ __('app.common.type') }}</th>
                    <th class="text-right">{{ __('app.common.quantity') }}</th>
                    <th class="text-right">{{ __('app.mutasi.balance') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mutasis as $mutasi)
                <tr>
                    <td style="color:var(--text-muted);">{{ $mutasis->firstItem() + $loop->index }}</td>
                    <td style="font-size:12px; color:var(--text-muted);">
                        {{ $mutasi->created_at->format('d M Y') }}<br>
                        <span style="font-size:11px;">{{ $mutasi->created_at->format('H:i') }}</span>
                    </td>
                    <td>
                        @if($mutasi->material)
                            <div style="font-weight:500;">{{ $mutasi->material->name }}</div>
                            <div style="font-size:11px; color:var(--text-muted);">MAT: {{ $mutasi->material->code }}</div>
                        @elseif($mutasi->part)
                            <div style="font-weight:500;">{{ $mutasi->part->part_name }}</div>
                            <div style="font-size:11px; color:var(--text-muted);">PART: {{ $mutasi->part->part_no }}</div>
                        @endif
                    </td>
                    <td>{{ $mutasi->warehouse->name ?? '-' }}</td>
                    <td style="font-size: 12px; color: var(--text-muted);">
                        {{ $mutasi->notes ?: '-' }}
                    </td>
                    <td>
                        @if($mutasi->type === 'in' && $mutasi->reference_type === 'App\Models\ReturnGi')
                            <span class="badge badge-warning">IN RETUR</span>
                        @elseif($mutasi->type === 'in')
                            <span class="badge badge-success">IN</span>
                        @elseif($mutasi->type === 'out')
                            <span class="badge badge-danger">OUT</span>
                        @else
                            <span class="badge badge-secondary">{{ strtoupper($mutasi->type) }}</span>
                        @endif
                    </td>
                    <td class="text-right" style="font-weight:600; color:{{ $mutasi->type === 'in' ? 'var(--success)' : 'var(--danger)' }};">
                        {{ $mutasi->type === 'in' ? '+' : '-' }}{{ number_format($mutasi->quantity, 2) }}
                    </td>
                    <td class="text-right" style="font-weight:600;">
                        {{ number_format($mutasi->balance, 2) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state" style="padding: 60px 20px;">
                            <i class="fas fa-history"></i>
                            <h4>{{ __('app.mutasi.empty_title') }}</h4>
                            <p>{{ __('app.mutasi.empty_desc') }}</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($mutasis->hasPages())
    <div style="padding: 16px 20px; border-top: 1px solid var(--border); display:flex; justify-content:space-between; align-items:center;">
        <div style="font-size:13px; color:var(--text-muted);">
            {{ __('app.pagination.showing', ['from'=>$mutasis->firstItem(), 'to'=>$mutasis->lastItem(), 'total'=>$mutasis->total(), 'entity'=>__('app.mutasi.entity')]) }}
        </div>
        <div style="display:flex; gap:6px;">
            {{ $mutasis->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
