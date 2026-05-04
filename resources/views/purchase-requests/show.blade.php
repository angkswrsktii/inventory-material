@extends('layouts.app')
@section('title', 'Detail Purchase Request')
@section('topbar-title', 'Purchase Request')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('purchase-requests.index') }}">Purchase Request</a>
    <span class="sep">/</span>
    <span>{{ $purchaseRequest->document_no }}</span>
</div>

{{-- Header --}}
<div class="page-header">
    <div>
        <div class="page-title" style="display:flex;align-items:center;gap:12px;">
            {{ $purchaseRequest->document_no }}
            @php $color = $purchaseRequest->status_color @endphp
            <span class="badge badge-{{ $color }}" style="font-size:12px;">
                {{ $purchaseRequest->status_label }}
            </span>
        </div>
        <div class="page-subtitle">
            Dibuat oleh {{ $purchaseRequest->creator?->name ?? '—' }}
            · {{ $purchaseRequest->created_at->format('d M Y, H:i') }}
        </div>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <a href="{{ route('purchase-requests.print', $purchaseRequest) }}" target="_blank" class="btn btn-secondary">
            <i class="fas fa-print"></i> Print
        </a>
        @if($purchaseRequest->canEdit())
            <a href="{{ route('purchase-requests.edit', $purchaseRequest) }}" class="btn btn-secondary">
                <i class="fas fa-pen"></i> Edit
            </a>
        @endif
        @if($purchaseRequest->canSubmit())
            <form action="{{ route('purchase-requests.submit', $purchaseRequest) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary" onclick="return confirm('Ajukan PR ini untuk review?')">
                    <i class="fas fa-paper-plane"></i> Ajukan
                </button>
            </form>
        @endif
        @if($purchaseRequest->canMarkOrdered() && auth()->user()->canApprove())
            <a href="{{ route('purchase-orders.create', ['pr_id' => $purchaseRequest->id]) }}" class="btn btn-primary">
                <i class="fas fa-cart-shopping"></i> Buat PO
            </a>
        @endif
    </div>
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

<div style="display:grid; grid-template-columns:1fr 320px; gap:20px; align-items:start;">

    {{-- LEFT: Items --}}
    <div>
        <div class="card">
            <div class="card-header">
                <span class="card-title">
                    <i class="fas fa-boxes-stacked" style="color:var(--accent);margin-right:8px;"></i>
                    Daftar Material yang Diminta
                </span>
            </div>

            {{-- Approve form --}}
            @if($purchaseRequest->canReview() && auth()->user()->canApprove())
            <form action="{{ route('purchase-requests.approve', $purchaseRequest) }}" method="POST" id="approveForm">
            @csrf
            @endif

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Material / Barang</th>
                            <th>Kode</th>
                            <th>Satuan</th>
                            <th>Spesifikasi</th>
                            <th class="text-right">Qty Diminta</th>
                            @if(in_array($purchaseRequest->status, ['approved','ordered']))
                                <th class="text-right">Qty Disetujui</th>
                            @elseif($purchaseRequest->canReview() && auth()->user()->canApprove())
                                <th class="text-right">Qty Disetujui</th>
                            @endif
                            <th class="text-right">Harga Est.</th>
                            <th class="text-right">Subtotal</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchaseRequest->items as $i => $item)
                        <tr>
                            <td style="color:var(--text-muted);font-size:12px;">{{ $i+1 }}</td>
                            <td style="font-weight:500;">{{ $item->material_name }}</td>
                            <td><span class="mono" style="font-size:11px;color:var(--text-muted);">{{ $item->material_code ?: '—' }}</span></td>
                            <td>{{ $item->unit }}</td>
                            <td style="font-size:12px;color:var(--text-muted);">{{ $item->specification ?: '—' }}</td>
                            <td class="text-right">
                                <strong>{{ number_format($item->quantity_requested, 2) }}</strong>
                            </td>
                            @if(in_array($purchaseRequest->status, ['approved','ordered']))
                                <td class="text-right">
                                    @if($item->quantity_approved !== null)
                                        <span style="color:var(--success);font-weight:600;">
                                            {{ number_format($item->quantity_approved, 2) }}
                                        </span>
                                    @else
                                        <span style="color:var(--text-dim);">—</span>
                                    @endif
                                </td>
                            @elseif($purchaseRequest->canReview() && auth()->user()->canApprove())
                                <td class="text-right">
                                    <input type="number" name="approved_quantities[{{ $item->id }}]"
                                           class="form-control text-right"
                                           style="width:90px;display:inline-block;"
                                           min="0" step="0.01"
                                           value="{{ $item->quantity_requested }}"
                                           placeholder="{{ $item->quantity_requested }}">
                                </td>
                            @endif
                            <td class="text-right" style="font-size:12px;color:var(--text-muted);">
                                {{ $item->estimated_price ? 'Rp '.number_format($item->estimated_price, 0, ',', '.') : '—' }}
                            </td>
                            <td class="text-right" style="font-size:12px;">
                                @if($item->estimated_price)
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td style="font-size:12px;color:var(--text-muted);">{{ $item->item_notes ?: '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="{{ $purchaseRequest->canReview() && auth()->user()->canApprove() ? 8 : (in_array($purchaseRequest->status, ['approved','ordered']) ? 8 : 7) }}"
                                style="text-align:right;font-size:12px;color:var(--text-muted);padding:12px 16px;">
                                <strong>Total Estimasi</strong>
                            </td>
                            <td style="text-align:right;padding:12px 16px;">
                                <strong style="color:var(--accent);">
                                    Rp {{ number_format($purchaseRequest->total_estimated, 0, ',', '.') }}
                                </strong>
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Approve/Reject actions --}}
            @if($purchaseRequest->canReview() && auth()->user()->canApprove())
                <div style="padding:16px 20px; border-top:1px solid var(--border); background:var(--surface-2); display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                    <button type="submit" form="approveForm" class="btn btn-success">
                        <i class="fas fa-check"></i> Setujui PR
                    </button>
                    <span style="color:var(--text-dim);font-size:12px;">atau</span>
                    <button type="button" class="btn btn-danger" onclick="document.getElementById('rejectModal').style.display='flex'">
                        <i class="fas fa-times"></i> Tolak PR
                    </button>
                </div>
            </form>
            @endif

        </div>

        {{-- Rejection reason --}}
        @if($purchaseRequest->status === 'rejected' && $purchaseRequest->rejection_reason)
        <div class="card" style="border-color:var(--danger-bg); margin-top:16px;">
            <div class="card-header">
                <span class="card-title" style="color:var(--danger);">
                    <i class="fas fa-circle-xmark"></i> Alasan Penolakan
                </span>
            </div>
            <div class="card-body">
                <p style="color:var(--text-muted);">{{ $purchaseRequest->rejection_reason }}</p>
            </div>
        </div>
        @endif
    </div>

    {{-- RIGHT: Info sidebar --}}
    <div style="display:flex;flex-direction:column;gap:16px;">

        <div class="card">
            <div class="card-header">
                <span class="card-title">
                    <i class="fas fa-circle-info" style="color:var(--accent-2);margin-right:8px;"></i>
                    Detail Permintaan
                </span>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">
                <div>
                    <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;letter-spacing:.5px;">Tanggal Request</div>
                    <div style="font-weight:500;margin-top:3px;">{{ $purchaseRequest->request_date->format('d F Y') }}</div>
                </div>
                <div>
                    <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;letter-spacing:.5px;">Pemohon</div>
                    <div style="font-weight:500;margin-top:3px;">{{ $purchaseRequest->requested_by_name }}</div>
                </div>
                @if($purchaseRequest->department)
                <div>
                    <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;letter-spacing:.5px;">Departemen</div>
                    <div style="margin-top:3px;"><span class="badge badge-muted">{{ $purchaseRequest->department }}</span></div>
                </div>
                @endif
                @if($purchaseRequest->purpose)
                <div>
                    <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;letter-spacing:.5px;">Keperluan</div>
                    <div style="font-size:13px;color:var(--text-muted);margin-top:3px;">{{ $purchaseRequest->purpose }}</div>
                </div>
                @endif
                @if($purchaseRequest->notes)
                <div>
                    <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;letter-spacing:.5px;">Catatan</div>
                    <div style="font-size:13px;color:var(--text-muted);margin-top:3px;">{{ $purchaseRequest->notes }}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- Review info --}}
        @if($purchaseRequest->reviewer)
        <div class="card">
            <div class="card-header">
                <span class="card-title">
                    <i class="fas fa-user-check" style="color:var(--success);margin-right:8px;"></i>
                    Info Review
                </span>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:12px;">
                <div>
                    <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;letter-spacing:.5px;">Direviu oleh</div>
                    <div style="font-weight:500;margin-top:3px;">{{ $purchaseRequest->reviewer->name }}</div>
                    <div style="font-size:12px;color:var(--text-muted);">{{ $purchaseRequest->reviewer->role_label }}</div>
                </div>
                <div>
                    <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;letter-spacing:.5px;">Waktu Review</div>
                    <div style="font-size:13px;margin-top:3px;">{{ $purchaseRequest->reviewed_at?->format('d M Y, H:i') }}</div>
                </div>
            </div>
        </div>
        @endif

        {{-- Delete button for draft/rejected --}}
        @if($purchaseRequest->canEdit())
        <form action="{{ route('purchase-requests.destroy', $purchaseRequest) }}" method="POST"
              onsubmit="return confirm('Yakin ingin menghapus PR ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger" style="width:100%;">
                <i class="fas fa-trash"></i> Hapus PR
            </button>
        </form>
        @endif

    </div>
</div>

{{-- Reject Modal --}}
@if($purchaseRequest->canReview() && auth()->user()->canApprove())
<div id="rejectModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:999;align-items:center;justify-content:center;">
    <div class="card" style="width:420px;max-width:90vw;">
        <div class="card-header">
            <span class="card-title" style="color:var(--danger);"><i class="fas fa-circle-xmark"></i> Tolak Purchase Request</span>
            <button type="button" onclick="document.getElementById('rejectModal').style.display='none'" class="btn btn-ghost btn-xs"><i class="fas fa-times"></i></button>
        </div>
        <div class="card-body">
            <form action="{{ route('purchase-requests.reject', $purchaseRequest) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Alasan Penolakan <span class="required">*</span></label>
                    <textarea name="rejection_reason" class="form-control" rows="3" required
                              placeholder="Jelaskan alasan penolakan PR ini..."></textarea>
                </div>
                <div style="display:flex;gap:8px;margin-top:16px;">
                    <button type="submit" class="btn btn-danger" style="flex:1;">
                        <i class="fas fa-times"></i> Tolak PR
                    </button>
                    <button type="button" class="btn btn-ghost" onclick="document.getElementById('rejectModal').style.display='none'">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection