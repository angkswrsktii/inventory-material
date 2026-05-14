@extends('layouts.app')
@section('title', 'Edit Work Order')
@section('topbar-title', 'Work Order (Quality Check)')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('production-qc.index') }}">Work Order</a>
    <span class="sep">/</span>
    <a href="{{ route('production-qc.show', $productionQc) }}">{{ $productionQc->wo_number }}</a>
    <span class="sep">/</span>
    <span>Edit</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">Edit Work Order (QC)</div>
        <div class="page-subtitle">{{ $productionQc->wo_number }}</div>
    </div>
</div>

<div style="max-width:760px;">
    <form action="{{ route('production-qc.update', $productionQc) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Info Good Issue --}}
        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-link" style="color:var(--accent);margin-right:8px;"></i>Dari Good Issue (Terkunci)</span>
                <span style="font-family:monospace;font-size:12px;color:var(--accent);">{{ $productionQc->goodIssue->gi_number }}</span>
            </div>
            <div class="card-body" style="opacity: 0.8; background:var(--surface-1);">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div>
                        <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;">Target Produksi (Part)</div>
                        <div style="font-weight:600;margin-top:3px;">{{ $productionQc->part->part_name ?? '-' }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;">Tujuan / Catatan</div>
                        <div style="margin-top:3px;color:var(--text-muted);">{{ $productionQc->goodIssue->purpose }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Hasil QC --}}
        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-edit" style="color:var(--accent-2);margin-right:8px;"></i>Update Hasil QC</span>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Tanggal Work Order <span class="required">*</span></label>
                    <input type="date" name="qc_date" class="form-control" style="width: 200px;" value="{{ old('qc_date', $productionQc->qc_date->format('Y-m-d')) }}" required>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px; margin-bottom: 20px; background:var(--surface-2); padding:15px; border-radius:8px;">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" style="color:var(--success);">
                            <i class="fas fa-check-circle"></i> OK (Good Part)
                        </label>
                        <input type="number" name="quantity_passed" class="form-control" value="{{ old('quantity_passed', $productionQc->quantity_passed) }}" min="0" step="0.01" required style="border-color:var(--success); font-size: 16px; font-weight: bold;">
                    </div>
                    
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" style="color:var(--danger);">
                            <i class="fas fa-times-circle"></i> NG (Buang)
                        </label>
                        <input type="number" name="quantity_failed" id="qtyFailed" class="form-control" value="{{ old('quantity_failed', $productionQc->quantity_failed) }}" min="0" step="0.01" required oninput="calculateTotalNG()" style="border-color:var(--danger); font-size: 16px; font-weight: bold;">
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" style="color:var(--warning);">
                            <i class="fas fa-recycle"></i> NG (Jadi Material)
                        </label>
                        <input type="number" name="quantity_failed_retur" id="qtyRetur" class="form-control" value="{{ old('quantity_failed_retur', $productionQc->quantity_failed_retur) }}" min="0" step="0.01" required oninput="calculateTotalNG()" style="border-color:var(--warning); font-size: 16px; font-weight: bold;">
                    </div>
                </div>

                <div style="margin-bottom:20px; padding:10px 15px; background:rgba(239, 68, 68, 0.1); border-left:4px solid var(--danger); border-radius:4px;">
                    <span style="font-weight:600; font-size:14px;">Total Not Good (NG): </span>
                    <span id="displayTotalNG" style="color:var(--danger); font-weight:800; font-size:18px;">{{ $productionQc->total_ng }}</span>
                </div>

                <div class="form-group" style="margin-top:14px;">
                    <label class="form-label">Catatan / Keterangan</label>
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $productionQc->notes) }}</textarea>
                </div>
            </div>
        </div>

        <div style="display:flex;gap:10px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Work Order
            </button>
            <a href="{{ route('production-qc.index') }}" class="btn btn-ghost">
                Batal
            </a>
        </div>
    </form>

    <script>
        function calculateTotalNG() {
            let ng1 = parseFloat(document.getElementById('qtyFailed').value) || 0;
            let ng2 = parseFloat(document.getElementById('qtyRetur').value) || 0;
            document.getElementById('displayTotalNG').innerText = (ng1 + ng2).toFixed(2);
        }
    </script>
</div>
@endsection