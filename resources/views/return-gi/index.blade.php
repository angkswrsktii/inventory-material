@extends('layouts.app')
@section('title', 'Recycle Good Issue')
@section('topbar-title', __('app.nav.good_issue') . ' — ' . __('app.nav.good_issue'))

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Recycle Good Issue</div>
        <div class="page-subtitle">Pengembalian material sisa atau NG dari Work Order (QC) ke {{ __('app.warehouse.title') }}</div>
    </div>
    <a href="{{ route('return-gi.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Buat Recycle Baru
    </a>
</div>


<div class="card" style="margin-bottom: 20px;">
    <div class="card-body" style="padding: 16px 20px;">
        <form method="GET" action="{{ route('return-gi.index') }}" style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
            <div style="flex:1; min-width:200px; position:relative;">
                <i class="fas fa-search" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:13px;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. Retur, No. WO, No. GI..."
                    style="width:100%; background:var(--surface-2); border:1px solid var(--border); color:var(--text); padding:8px 12px 8px 34px; border-radius:var(--radius-sm); font-family:inherit; font-size:13px; outline:none;">
            </div>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> {{ __('app.btn.search') }}</button>
            @if(request('search'))
                <a href="{{ route('return-gi.index') }}" class="btn btn-ghost btn-sm"><i class="fas fa-times"></i> {{ __('app.btn.reset') }}</a>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No. Retur</th>
                    <th>No. WO (QC)</th>
                    <th>No. GI Asal</th>
                    <th>Tanggal Retur</th>
                    <th>Oleh</th>
                    <th class="text-center" width="100">{{ __('app.common.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($returns as $ret)
                <tr>
                    <td>
                        <span style="font-family:'Syne',sans-serif; font-size:12px; font-weight:600; color:var(--accent); background:var(--accent-glow); padding:3px 8px; border-radius:4px; white-space:nowrap;">
                            {{ $ret->return_number }}
                        </span>
                    </td>
                    <td>
                        @if($ret->productionQc)
                            <a href="{{ route('production-qc.show', $ret->productionQc) }}" class="mono" style="color:var(--accent); font-weight:bold; font-size:12px; text-decoration:none;">{{ $ret->productionQc->wo_number }}</a>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($ret->goodIssue)
                            <a href="{{ route('good-issues.show', $ret->goodIssue) }}" class="mono" style="color:var(--text); font-size:12px; text-decoration:none;">{{ $ret->goodIssue->gi_number }}</a>
                        @else
                            -
                        @endif
                    </td>
                    <td style="color:var(--text-muted); font-size:13px;">{{ $ret->return_date->format('d M Y') }}</td>
                    <td>{{ $ret->returner->name ?? '-' }}</td>
                    <td class="text-center">
                        <div style="display:flex; gap:6px; justify-content:center;">
                            <a href="{{ route('return-gi.show', $ret) }}" class="btn btn-ghost btn-sm" title="Detail"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('return-gi.print', $ret) }}" target="_blank" class="btn btn-ghost btn-sm" title="Print" style="color:var(--accent);"><i class="fas fa-print"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state" style="padding: 60px 20px; text-align:center;">
                            <i class="fas fa-undo" style="font-size:24px; color:var(--text-dim); margin-bottom:10px;"></i>
                            <h4>{{ __("app.return_gi.empty_title") }}</h4>
                            <p style="color:var(--text-dim); font-size:13px;">Mulai catat pengembalian material dari WO (QC)</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($returns->hasPages())
    <div style="padding: 16px 20px; border-top: 1px solid var(--border);">
        {{ $returns->links() }}
    </div>
    @endif
</div>
@endsection