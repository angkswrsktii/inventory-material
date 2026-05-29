@extends('layouts.app')
@section('title', __('app.production_qc.page_title'))
@section('topbar-title', __('app.nav.work_order') . ' — ' . __('app.nav.quality_check'))

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">{{ __('app.production_qc.title') }}</div>
        <div class="page-subtitle">{{ __('app.production_qc.subtitle') }}</div>
    </div>
    <a href="{{ route('production-qc.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> {{ __('app.production_qc.add') }}
    </a>
</div>

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:20px;">
    <div class="card" style="padding:20px;">
        <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;letter-spacing:.5px;">{{ __('app.production_qc.stat_total_wo') }}</div>
        <div style="font-size:28px;font-weight:800;font-family:'Syne',sans-serif;color:var(--accent);">{{ $stats['total'] ?? 0 }}</div>
    </div>
    <div class="card" style="padding:20px;">
        <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;letter-spacing:.5px;">{{ __('app.production_qc.stat_total_ok') }}</div>
        <div style="font-size:28px;font-weight:800;font-family:'Syne',sans-serif;color:var(--success);">{{ number_format($stats['total_ok'] ?? 0, 2) }}</div>
    </div>
    <div class="card" style="padding:20px;">
        <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;letter-spacing:.5px;">{{ __('app.production_qc.stat_total_ng') }}</div>
        <div style="font-size:28px;font-weight:800;font-family:'Syne',sans-serif;color:var(--danger);">{{ number_format($stats['total_ng'] ?? 0, 2) }}</div>
    </div>
</div>

<div class="card">
    <div class="card-header" style="gap:12px;flex-wrap:wrap;">
        <form method="GET" style="display:flex;gap:10px;flex:1;flex-wrap:wrap;">
            <div style="position:relative;flex:1;min-width:200px;">
                <i class="fas fa-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-dim);font-size:13px;"></i>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                       style="padding-left:36px;" placeholder="{{ __('app.production_qc.search_placeholder') }}">
            </div>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control" style="width:150px;">
            <input type="date" name="date_to"   value="{{ request('date_to') }}"   class="form-control" style="width:150px;">
            <button type="submit" class="btn btn-secondary"><i class="fas fa-filter"></i> {{ __('app.btn.filter') }}</button>
            @if(request()->hasAny(['search','date_from','date_to']))
                <a href="{{ route('production-qc.index') }}" class="btn btn-ghost">{{ __('app.btn.reset') }}</a>
            @endif
        </form>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>{{ __('app.production_qc.col_wo') }}</th>
                    <th>{{ __('app.production_qc.col_gi') }}</th>
                    <th>{{ __('app.production_qc.col_part') }}</th>
                    <th>{{ __('app.production_qc.col_qc_date') }}</th>
                    <th class="text-right" style="color:var(--success);">{{ __('app.production_qc.col_ok') }}</th>
                    <th class="text-right" style="color:var(--danger);">{{ __('app.production_qc.col_ng') }}</th>
                    <th class="text-center">{{ __('app.common.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($qcs as $qc)
                <tr>
                    <td>
                        <a href="{{ route('production-qc.show', $qc) }}" style="font-family:monospace;font-size:12px;color:var(--accent);font-weight:600;">
                            {{ $qc->wo_number }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('good-issues.show', $qc->goodIssue) }}" style="font-family:monospace;font-size:12px;color:var(--text-muted);">
                            {{ $qc->goodIssue->gi_number }}
                        </a>
                    </td>
                    <td style="font-size:13px;">{{ $qc->part->part_name ?? '-' }}</td>
                    <td style="font-size:12px;color:var(--text-muted);">{{ $qc->qc_date->format('d M Y') }}</td>
                    <td class="text-right" style="color:var(--success);font-weight:600;">
                        {{ number_format($qc->quantity_passed, 2) }}
                    </td>
                    <td class="text-right" style="color:var(--danger);font-weight:600;">
                        {{ number_format($qc->total_ng, 2) }}
                    </td>
                    <td class="text-center">
                        <div style="display:flex;gap:6px;justify-content:center;">
                            <a href="{{ route('production-qc.show', $qc) }}" class="btn btn-xs btn-secondary" title="{{ __('app.btn.detail') }}">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('production-qc.print', $qc) }}" target="_blank" class="btn btn-xs" style="background-color:var(--accent);color:white;" title="{{ __('app.btn.print') }}">
                                <i class="fas fa-print"></i>
                            </a>
                            <a href="{{ route('production-qc.edit', $qc) }}" class="btn btn-xs btn-warning" title="{{ __('app.btn.edit') }}">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('production-qc.destroy', $qc) }}" method="POST" data-confirm="{{ __('app.common.confirm_delete') }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-danger" title="{{ __('app.btn.delete') }}"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:40px;color:var(--text-dim);">
                        <i class="fas fa-clipboard-check" style="font-size:28px;margin-bottom:10px;display:block;"></i>
                        {!! __('app.production_qc.empty_desc') !!}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($qcs->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border);display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
        @if($qcs->onFirstPage())
            <span class="btn btn-xs btn-ghost" style="opacity:0.4;">{{ __('app.common.prev') }}</span>
        @else
            <a href="{{ $qcs->previousPageUrl() }}" class="btn btn-xs btn-secondary">{{ __('app.common.prev') }}</a>
        @endif
        @foreach($qcs->getUrlRange(1, $qcs->lastPage()) as $page => $url)
            @if($page == $qcs->currentPage())
                <span class="btn btn-xs btn-primary">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="btn btn-xs btn-secondary">{{ $page }}</a>
            @endif
        @endforeach
        @if($qcs->hasMorePages())
            <a href="{{ $qcs->nextPageUrl() }}" class="btn btn-xs btn-secondary">{{ __('app.common.next') }}</a>
        @else
            <span class="btn btn-xs btn-ghost" style="opacity:0.4;">{{ __('app.common.next') }}</span>
        @endif
    </div>
    @endif
</div>
@endsection
