@extends('layouts.app')
@section('title', 'Detail Work Order')
@section('topbar-title', __('app.nav.work_order') . ' — ' . __('app.nav.quality_check'))

@section('content')
<div class="breadcrumb">
    <a href="{{ route('production-qc.index') }}">Work Order</a>
    <span class="sep">/</span>
    <span>{{ $productionQc->wo_number }}</span>
</div>

<div class="page-header">
    <div style="display:flex;align-items:center;gap:12px;">
        <div class="page-title">{{ $productionQc->wo_number }}</div>
        @if($productionQc->status == 'approved')
            <span class="badge badge-success">Disetujui</span>
        @else
            <span class="badge badge-warning">Draft</span>
        @endif
    </div>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('production-qc.print', $productionQc) }}" target="_blank" class="btn btn-secondary">
            <i class="fas fa-print"></i> Cetak Dokumen
        </a>

        @if($productionQc->status === 'draft')
        <a href="{{ route('production-qc.edit', $productionQc) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit
        </a>
        <form action="{{ route('production-qc.approve', $productionQc) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success" onclick="return confirm('Setujui Work Order ini? Stok Part (Good) akan bertambah.')">
                <i class="fas fa-check"></i> Approve
            </button>
        </form>
        @endif
    </div>
</div>


<div style="display:grid;grid-template-columns:1fr 320px;gap:20px;align-items:start;">

    <div style="display:flex;flex-direction:column;gap:20px;">

        {{-- Good Issue terkait --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-link" style="color:var(--accent);margin-right:8px;"></i>Dari Good Issue</span>
                <a href="{{ route('good-issues.show', $productionQc->goodIssue) }}" style="font-family:monospace;font-size:12px;color:var(--accent);">
                    {{ $productionQc->goodIssue->gi_number }}
                </a>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
                    <div>
                        <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;">Target Produksi (Part)</div>
                        <div style="font-weight:600;margin-top:3px;">{{ $productionQc->part->part_name ?? '-' }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;">PIC Pemotong</div>
                        <div style="margin-top:3px;color:var(--text-muted);">{{ $productionQc->goodIssue->pic->name ?? '-' }}</div>
                    </div>
                </div>
                <div style="font-size:12px;color:var(--text-dim);margin-bottom:8px;text-transform:uppercase;letter-spacing:.5px;">Material yang Dikeluarkan</div>
                <div style="background:var(--surface-2);border-radius:8px;overflow:hidden;">
                    @foreach($productionQc->goodIssue->items as $item)
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 14px;border-bottom:1px solid var(--border);">
                        <div>
                            <span style="font-weight:600;font-size:13px;">{{ $item->material->name ?? '-' }}</span>
                        </div>
                        <span style="color:var(--danger);font-weight:600;">{{ number_format($item->quantity,2) }} {{ $item->unit ?? 'Pcs' }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Hasil QC --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-clipboard-check" style="color:var(--accent-2);margin-right:8px;"></i>Hasil Work Order (QC)</span>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:repeat(3, 1fr);gap:14px;margin-bottom:16px;">
                    <div style="padding:16px;border:2px solid var(--success);border-radius:10px;text-align:center;">
                        <div style="font-size:11px;color:var(--success);text-transform:uppercase;"><i class="fas fa-check-circle"></i> OK (Good Part)</div>
                        <div style="font-size:26px;font-weight:800;color:var(--success);margin-top:4px;">{{ number_format($productionQc->quantity_passed, 2) }}</div>
                    </div>
                    
                    <div style="padding:16px;border:2px solid var(--danger);border-radius:10px;text-align:center;">
                        <div style="font-size:11px;color:var(--danger);text-transform:uppercase;"><i class="fas fa-times-circle"></i> NG (Scrap)</div>
                        <div style="font-size:26px;font-weight:800;color:var(--danger);margin-top:4px;">{{ number_format($productionQc->quantity_failed, 2) }}</div>
                    </div>

                    <div style="padding:16px;border:2px solid var(--warning);border-radius:10px;text-align:center;">
                        <div style="font-size:11px;color:var(--warning);text-transform:uppercase;"><i class="fas fa-recycle"></i> NG (Retur)</div>
                        <div style="font-size:26px;font-weight:800;color:var(--warning);margin-top:4px;">{{ number_format($productionQc->quantity_failed_retur, 2) }}</div>
                    </div>
                </div>

                <div style="background:var(--surface-2); padding:15px; border-radius:8px; display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                    <div style="font-weight:600; font-size:14px; text-transform:uppercase;">Total Not Good (NG) Keseluruhan:</div>
                    <div style="font-size:22px; font-weight:800; color:var(--danger);">{{ number_format($productionQc->total_ng, 2) }}</div>
                </div>

                @if($productionQc->notes)
                <div style="padding:12px;background:var(--surface-2);border-radius:8px;font-size:13px;color:var(--text-muted);">
                    <div style="font-size:11px;color:var(--text-dim);margin-bottom:4px;">CATATAN / KETERANGAN</div>
                    {{ $productionQc->notes }}
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Panel kanan --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-info-circle" style="color:var(--accent-2);margin-right:8px;"></i>Detail Dokumen</span>
        </div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">
            @foreach([
                ['No. WO',      $productionQc->wo_number],
                ['Tanggal QC',  $productionQc->qc_date->format('d M Y')],
                ['Dibuat Oleh', $productionQc->checker?->name],
                ['Tgl. Dibuat', $productionQc->created_at->format('d M Y H:i')],
            ] as [$label, $value])
            <div>
                <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;letter-spacing:.5px;">{{ $label }}</div>
                <div style="margin-top:3px;color:var(--text-muted);font-weight:500;">{{ $value }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection