@extends('layouts.app')

@section('title', 'Purchase Order')
@section('topbar-title', __('app.nav.purchasing') . ' — ' . __('app.nav.purchase_order'))

@section('topbar-actions')
    <span style="font-size:12px; color: var(--text-muted);">
        <i class="fas fa-clock"></i>
        {{ now()->format('d M Y, H:i') }}
    </span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Purchase Order</div>
        <div class="page-subtitle">{{ __('app.po.subtitle') }}</div>
    </div>
    <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> {{ __("app.po.add") }}
    </a>
</div>

<!-- Filter -->
<div class="card" style="margin-bottom: 20px;">
    <div class="card-body" style="padding: 16px 20px;">
        <form method="GET" action="{{ route('purchase-orders.index') }}" style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
            <div style="flex:1; min-width:200px; position:relative;">
                <i class="fas fa-search" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:13px;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('app.po.search_placeholder') }}"
                    style="width:100%; background:var(--surface-2); border:1px solid var(--border); color:var(--text); padding:8px 12px 8px 34px; border-radius:var(--radius-sm); font-family:inherit; font-size:13px; outline:none;">
            </div>
           <select name="status" style="background:var(--surface-2); border:1px solid var(--border); color:var(--text); padding:8px 12px; border-radius:var(--radius-sm); font-family:inherit; font-size:13px; outline:none;">
                <option value="">{{ __('app.common.all_status') }}</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="issued" {{ request('status') === 'issued' ? 'selected' : '' }}>Issued</option>
                <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>Partial</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> {{ __('app.btn.search') }}</button>
            @if(request('search') || request('status'))
                <a href="{{ route('purchase-orders.index') }}" class="btn btn-ghost btn-sm"><i class="fas fa-times"></i> {{ __('app.btn.reset') }}</a>
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
                    <th>{{ __('app.common.po_number') }}</th>
                    <th>No. PR</th>
                    <th>{{ __('app.common.supplier') }}</th>
                    <th>{{ __('app.supplier.po_date') }}</th>
                    <th>{{ __('app.common.total_item') }}</th>
                    <th>{{ __('app.common.status') }}</th>
                    <th width="110">{{ __('app.common.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchaseOrders as $po)
                <tr>
                    <td style="color:var(--text-muted);">{{ $purchaseOrders->firstItem() + $loop->index }}</td>
                    <td>
                        <span style="font-family:'Syne',sans-serif; font-size:12px; font-weight:600; color:var(--accent); background:var(--accent-glow); padding:3px 8px; border-radius:4px; white-space:nowrap;">
                            {{ $po->po_number }}
                        </span>
                    </td>
                    <td>
                        @if($po->purchaseRequest)
                            <a href="{{ route('purchase-requests.show', $po->purchaseRequest) }}" class="mono" style="color:var(--text); text-decoration:none;">{{ $po->purchaseRequest->pr_number }}</a>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $po->supplier->name ?? '-' }}</td>
                    <td style="color:var(--text-muted);">{{ $po->order_date->format('d M Y') }}</td>
                    <td>{{ $po->items->count() }} Item</td>
                    <td>
                        @if($po->status === 'draft')
                            <span class="badge badge-secondary" style="background:#e2e8f0; color:#475569;">Draft</span>
                        @elseif($po->status === 'issued')
                            <span class="badge badge-primary">Issued</span>
                        @elseif($po->status === 'partial')
                            <span class="badge badge-info">Partial</span>
                        @elseif($po->status === 'completed')
                            <span class="badge badge-success">Completed</span>
                        @elseif($po->status === 'cancelled')
                            <span class="badge badge-danger">Cancelled</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex; gap:6px;">
                            <a href="{{ route('purchase-orders.show', $po) }}" class="btn btn-ghost btn-sm" title="Detail"><i class="fas fa-eye"></i></a>
                            
                            @if($po->status === 'draft')
                                <a href="{{ route('purchase-orders.edit', $po) }}" class="btn btn-ghost btn-sm" title="Edit"><i class="fas fa-pen"></i></a>
                            @endif

                            @if(in_array($po->status, ['draft', 'issued']))
                            <form method="POST" action="{{ route('purchase-orders.cancel', $po) }}" onsubmit="return confirmCancel(this)">
                                @csrf 
                                <input type="hidden" name="cancel_reason" class="cancel-reason-input">
                                <button type="submit" class="btn btn-ghost btn-sm" title="Cancel" style="color:var(--danger);">
                                    <i class="fas fa-ban"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state" style="padding: 60px 20px;">
                            <i class="fas fa-file-invoice-dollar"></i>
                            <h4>{{ __("app.po.empty_title") }}</h4>
                            <p>Mulai buat pesanan pembelian ke supplier</p>
                            <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary btn-sm" style="margin-top:12px;"><i class="fas fa-plus"></i> {{ __("app.po.add") }}</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    function confirmCancel(form) {
        let reason = prompt('Masukkan alasan pembatalan PO ini (minimal 5 karakter):');
        if (reason === null || reason.trim() === '') return false; 
        if (reason.trim().length < 5) {
            alert('Alasan pembatalan harus minimal 5 karakter!');
            return false; 
        }
        form.querySelector('.cancel-reason-input').value = reason;
        return true; 
    }
</script>
@endsection