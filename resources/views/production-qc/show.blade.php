@extends('layouts.app')
@section('title', 'Detail Quality Control Produksi')
@section('topbar-title', 'Quality Control Hasil Produksi')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('production-qc.index') }}">Quality Control Produksi</a>
    <span class="sep">/</span>
    <span>{{ $productionQc->document_no }}</span>
</div>

<div class="page-header">
    <div style="display:flex;align-items:center;gap:12px;">
        <div class="page-title">{{ $productionQc->document_no }}</div>
        <span class="badge badge-{{ $productionQc->status_color }}">{{ $productionQc->status_label }}</span>
    </div>
    <div style="display:flex;gap:8px;">
        @if($productionQc->status === 'draft' && auth()->user()->canApprove())
        <form action="{{ route('production-qc.approve', $productionQc) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success"
                    onclick="return confirm('Setujui Quality Control ini?')">
                <i class="fas fa-check"></i> Setujui
            </button>
        </form>
        <button type="button" class="btn btn-danger"
                onclick="document.getElementById('rejectModal').style.display='flex'">
            <i class="fas fa-times"></i> Tolak
        </button>
        @endif
    </div>
</div>

@if(session('success'))
<div class="alert alert-success" style="margin-bottom:16px;"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger" style="margin-bottom:16px;"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
@endif

<div style="display:grid;grid-template-columns:1fr 320px;gap:20px;align-items:start;">

    <div style="display:flex;flex-direction:column;gap:20px;">

        {{-- Kartu Pengambilan terkait --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-link" style="color:var(--accent);margin-right:8px;"></i>Dari Kartu Pengambilan</span>
                <a href="{{ route('withdrawal-cards.show', $productionQc->withdrawalCard) }}"
                   style="font-family:monospace;font-size:12px;color:var(--accent);">
                    {{ $productionQc->withdrawalCard->document_no }}
                </a>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
                    <div>
                        <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;">Part Name</div>
                        <div style="font-weight:600;margin-top:3px;">{{ $productionQc->withdrawalCard->part_name }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;">Line / PIC</div>
                        <div style="margin-top:3px;color:var(--text-muted);">{{ $productionQc->withdrawalCard->line }} · {{ $productionQc->withdrawalCard->pic }}</div>
                    </div>
                </div>
                <div style="font-size:12px;color:var(--text-dim);margin-bottom:8px;text-transform:uppercase;letter-spacing:.5px;">Material yang Diambil</div>
                <div style="background:var(--surface-2);border-radius:8px;overflow:hidden;">
                    @foreach($productionQc->withdrawalCard->items as $item)
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 14px;border-bottom:1px solid var(--border);">
                        <div>
                            <span style="font-weight:600;font-size:13px;">{{ $item->material->name }}</span>
                            <span style="font-size:11px;color:var(--text-dim);margin-left:8px;">[{{ $item->material->code }}]</span>
                        </div>
                        <span style="color:var(--danger);font-weight:600;">{{ number_format($item->quantity,2) }} {{ $item->material->unit }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Hasil QC --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-microscope" style="color:var(--accent-2);margin-right:8px;"></i>Hasil QC</span>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;margin-bottom:16px;">
                    <div style="padding:16px;background:var(--surface-2);border-radius:10px;text-align:center;">
                        <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;">Qty Produksi</div>
                        <div style="font-size:26px;font-weight:800;font-family:'Syne',sans-serif;margin-top:4px;">{{ number_format($productionQc->qty_produksi,0) }}</div>
                    </div>
                    <div style="padding:16px;border:2px solid var(--success);border-radius:10px;text-align:center;">
                        <div style="font-size:11px;color:var(--success);text-transform:uppercase;"><i class="fas fa-check-circle"></i> SFG</div>
                        <div style="font-size:26px;font-weight:800;color:var(--success);margin-top:4px;">{{ number_format($productionQc->qty_sfg,0) }}</div>
                        <div style="font-size:11px;color:var(--text-dim);">Barang Jadi</div>
                    </div>
                    <div style="padding:16px;border:2px solid var(--danger);border-radius:10px;text-align:center;">
                        <div style="font-size:11px;color:var(--danger);text-transform:uppercase;"><i class="fas fa-times-circle"></i> NG</div>
                        <div style="font-size:26px;font-weight:800;color:var(--danger);margin-top:4px;">{{ number_format($productionQc->qty_ng,0) }}</div>
                        <div style="font-size:11px;color:var(--text-dim);">% NG: {{ $productionQc->ng_percentage }}%</div>
                    </div>
                </div>

                @if($productionQc->ng_notes)
                <div style="padding:12px 14px;background:rgba(239,68,68,0.08);border-left:3px solid var(--danger);border-radius:6px;margin-bottom:12px;">
                    <div style="font-size:11px;color:var(--danger);text-transform:uppercase;margin-bottom:4px;">Keterangan NG</div>
                    <div style="font-size:13px;color:var(--text-muted);">{{ $productionQc->ng_notes }}</div>
                </div>
                @endif

                @if($productionQc->notes)
                <div style="padding:12px;background:var(--surface-2);border-radius:8px;font-size:13px;color:var(--text-muted);">
                    <div style="font-size:11px;color:var(--text-dim);margin-bottom:4px;">CATATAN</div>
                    {{ $productionQc->notes }}
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Panel kanan --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-info-circle" style="color:var(--accent-2);margin-right:8px;"></i>Detail</span>
        </div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">
            @foreach([
                ['No. Dokumen', $productionQc->document_no],
                ['Tanggal QC',  $productionQc->qc_date->format('d M Y')],
                ['Gedung',      $productionQc->gedung ?: '—'],
                ['Dibuat Oleh', $productionQc->creator?->name],
                ['Tgl. Dibuat', $productionQc->created_at->format('d M Y H:i')],
            ] as [$label, $value])
            <div>
                <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;letter-spacing:.5px;">{{ $label }}</div>
                <div style="margin-top:3px;color:var(--text-muted);">{{ $value }}</div>
            </div>
            @endforeach

            @if($productionQc->approver)
            <div>
                <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;letter-spacing:.5px;">
                    {{ $productionQc->status === 'approved' ? 'Disetujui' : 'Ditolak' }} Oleh
                </div>
                <div style="margin-top:3px;color:var(--text-muted);">{{ $productionQc->approver->name }}</div>
                <div style="font-size:11px;color:var(--text-dim);">{{ $productionQc->approved_at->format('d M Y H:i') }}</div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div id="rejectModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);
     z-index:999;align-items:center;justify-content:center;">
    <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:28px;width:460px;max-width:90vw;">
        <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:16px;margin-bottom:6px;">Tolak QC</div>
        <form action="{{ route('production-qc.reject', $productionQc) }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Alasan Penolakan</label>
                <textarea name="reject_reason" class="form-control" rows="3" required></textarea>
            </div>
            <div style="display:flex;gap:10px;margin-top:16px;">
                <button type="submit" class="btn btn-danger"><i class="fas fa-times"></i> Tolak</button>
                <button type="button" class="btn btn-ghost"
                        onclick="document.getElementById('rejectModal').style.display='none'">Batal</button>
            </div>
        </form>
    </div>
</div>
@endsection