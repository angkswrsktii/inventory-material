@extends('layouts.app')

@section('title', 'Purchase Request')
@section('topbar-title', __('app.nav.purchasing') . ' — ' . __('app.nav.purchase_request'))

@section('topbar-actions')
    <span style="font-size:12px; color: var(--text-muted);">
        <i class="fas fa-clock"></i>
        {{ now()->format('d M Y, H:i') }}
    </span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Purchase Request</div>
        <div class="page-subtitle">{{ __('app.pr.subtitle') }}</div>
    </div>
    <a href="{{ route('purchase-requests.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> {{ __("app.pr.add") }}
    </a>
</div>

<!-- Filter -->
<div class="card" style="margin-bottom: 20px;">
    <div class="card-body" style="padding: 16px 20px;">
        <form method="GET" action="{{ route('purchase-requests.index') }}" style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
            <div style="flex:1; min-width:200px; position:relative;">
                <i class="fas fa-search" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:13px;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('app.pr.search_placeholder') }}"
                    style="width:100%; background:var(--surface-2); border:1px solid var(--border); color:var(--text); padding:8px 12px 8px 34px; border-radius:var(--radius-sm); font-family:inherit; font-size:13px; outline:none;">
            </div>
            <select name="status" style="background:var(--surface-2); border:1px solid var(--border); color:var(--text); padding:8px 12px; border-radius:var(--radius-sm); font-family:inherit; font-size:13px; outline:none;">
                <option value="">{{ __('app.common.all_status') }}</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed (PO)</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> {{ __('app.btn.search') }}</button>
            @if(request('search') || request('status'))
                <a href="{{ route('purchase-requests.index') }}" class="btn btn-ghost btn-sm"><i class="fas fa-times"></i> {{ __('app.btn.reset') }}</a>
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
                    <th>No. PR</th>
                    <th>{{ __('app.pr.date') }}</th>
                    <th>{{ __('app.common.created_by') }}</th>
                    <th>{{ __('app.common.status') }}</th>
                    <th>{{ __('app.common.notes') }}</th>
                    <th width="110">{{ __('app.common.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchaseRequests as $pr)
                <tr>
                    <td style="color:var(--text-muted);">{{ $purchaseRequests->firstItem() + $loop->index }}</td>
                    <td>
                        <span style="font-family:'Syne',sans-serif; font-size:12px; font-weight:600; color:var(--accent); background:var(--accent-glow); padding:3px 8px; border-radius:4px; white-space:nowrap;">
                            {{ $pr->pr_number }}
                        </span>
                    </td>
                    <td style="color:var(--text-muted);">{{ $pr->request_date->format('d M Y') }}</td>
                    <td>{{ $pr->creator->name ?? '-' }}</td>
                    <td>
                        @if($pr->status === 'draft')
                            <span class="badge badge-secondary" style="background:#e2e8f0; color:#475569;">Draft</span>
                        @elseif($pr->status === 'pending')
                            <span class="badge badge-warning">Pending</span>
                        @elseif($pr->status === 'approved')
                            <span class="badge badge-success">Approved</span>
                        @elseif($pr->status === 'rejected')
                            <span class="badge badge-danger">Rejected</span>
                        @elseif($pr->status === 'completed')
                            <span class="badge badge-primary">Completed (PO)</span>
                        @endif
                    </td>
                    <td>
                        @if($pr->notes)
                            <div style="font-size:12px; color:var(--text-muted);">{{ Str::limit($pr->notes, 30) }}</div>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <div style="display:flex; gap:6px;">
                            <a href="{{ route('purchase-requests.show', $pr) }}" class="btn btn-ghost btn-sm" title="Detail"><i class="fas fa-eye"></i></a>
                            
                            @if($pr->status === 'draft')
                            <a href="{{ route('purchase-requests.edit', $pr) }}" class="btn btn-ghost btn-sm" title="Edit"><i class="fas fa-pen"></i></a>
                            @endif

                            @if(in_array($pr->status, ['draft', 'rejected']))
                            <form method="POST" action="{{ route('purchase-requests.destroy', $pr) }}" onsubmit="return confirm('Yakin hapus PR ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-ghost btn-sm" title="{{ __('app.btn.delete') }}" style="color:var(--danger);"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state" style="padding: 60px 20px;">
                            <i class="fas fa-file-invoice"></i>
                            <h4>{{ __("app.pr.empty_title") }}</h4>
                            <p>Mulai buat permintaan pembelian pertama kamu</p>
                            <a href="{{ route('purchase-requests.create') }}" class="btn btn-primary btn-sm" style="margin-top:12px;"><i class="fas fa-plus"></i> {{ __("app.pr.add") }}</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($purchaseRequests->hasPages())
    <div style="padding: 16px 20px; border-top: 1px solid var(--border); display:flex; justify-content:space-between; align-items:center;">
        <div style="font-size:13px; color:var(--text-muted);">
            Menampilkan {{ $purchaseRequests->firstItem() }}–{{ $purchaseRequests->lastItem() }} dari {{ $purchaseRequests->total() }} PR
        </div>
        <div style="display:flex; gap:6px;">
            {{ $purchaseRequests->links() }}
        </div>
    </div>
    @endif
</div>
@endsection