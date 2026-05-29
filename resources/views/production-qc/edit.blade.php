@extends('layouts.app')
@section('title', __('app.production_qc.edit_title'))
@section('topbar-title', __('app.nav.work_order') . ' — ' . __('app.nav.quality_check'))

@section('content')
<div class="breadcrumb">
    <a href="{{ route('production-qc.index') }}">{{ __('app.nav.work_order') }}</a>
    <span class="sep">/</span>
    <a href="{{ route('production-qc.show', $productionQc) }}">{{ $productionQc->wo_number }}</a>
    <span class="sep">/</span>
    <span>{{ __('app.btn.edit') }}</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">{{ __('app.production_qc.edit_title') }}</div>
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
                <span class="card-title"><i class="fas fa-link" style="color:var(--accent);margin-right:8px;"></i>{{ __('app.production_qc.from_gi_locked') }}</span>
                <span style="font-family:monospace;font-size:12px;color:var(--accent);">{{ $productionQc->goodIssue->gi_number }}</span>
            </div>
            <div class="card-body" style="opacity:0.8; background:var(--surface-1);">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div>
                        <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;">{{ __('app.production_qc.production_target') }}</div>
                        <div style="font-weight:600;margin-top:3px;">{{ $productionQc->part->part_name ?? '-' }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;">{{ __('app.production_qc.purpose_notes') }}</div>
                        <div style="margin-top:3px;color:var(--text-muted);">{{ $productionQc->goodIssue->purpose }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Hasil QC --}}
        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-edit" style="color:var(--accent-2);margin-right:8px;"></i>{{ __('app.production_qc.update_result_title') }}</span>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">{{ __('app.production_qc.wo_date') }} <span class="required">*</span></label>
                    <input type="date" name="qc_date" class="form-control" style="width:200px;"
                           value="{{ old('qc_date', $productionQc->qc_date->format('Y-m-d')) }}" required>
                </div>

                <div style="background:var(--surface-2); padding:18px; border-radius:10px; margin-bottom:20px;">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:14px;">

                        {{-- OK --}}
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="color:var(--success); font-weight:600;">
                                <i class="fas fa-check-circle"></i> {{ __('app.production_qc.ok_label') }}
                            </label>
                            <div style="display:flex; gap:8px; align-items:center;">
                                <input type="number" name="quantity_passed" id="qtyPassed" class="form-control"
                                       value="{{ old('quantity_passed', $productionQc->quantity_passed) }}"
                                       min="0" step="0.01" required
                                       style="border-color:var(--success); font-size:18px; font-weight:700; flex:1;">
                                <select name="unit_passed" id="unitPassed" onchange="syncUnit('unitPassed','unitFailed')"
                                        style="height:42px; border-radius:8px; border:1px solid var(--border); background:var(--surface-3); color:var(--text); padding:0 10px; font-size:13px; cursor:pointer; min-width:80px;">
                                    @foreach(['pcs','kg','liter','meter','set','box','roll','unit'] as $u)
                                        <option value="{{ $u }}" {{ old('unit_passed','pcs') === $u ? 'selected' : '' }}>{{ $u }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div style="font-size:11px;color:var(--text-dim);margin-top:5px;">
                                <i class="fas fa-arrow-up" style="font-size:9px;"></i> {{ __('app.production_qc.ok_desc') }}
                            </div>
                        </div>

                        {{-- NG Scrap --}}
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="color:var(--danger); font-weight:600;">
                                <i class="fas fa-times-circle"></i> {{ __('app.production_qc.ng_scrap') }}
                            </label>
                            <div style="display:flex; gap:8px; align-items:center;">
                                <input type="number" name="quantity_failed" id="qtyFailed" class="form-control"
                                       value="{{ old('quantity_failed', $productionQc->quantity_failed) }}"
                                       min="0" step="0.01" required oninput="calculateTotalNG()"
                                       style="border-color:var(--danger); font-size:18px; font-weight:700; flex:1;">
                                <select name="unit_failed" id="unitFailed" onchange="syncUnit('unitFailed','unitPassed')"
                                        style="height:42px; border-radius:8px; border:1px solid var(--border); background:var(--surface-3); color:var(--text); padding:0 10px; font-size:13px; cursor:pointer; min-width:80px;">
                                    @foreach(['pcs','kg','liter','meter','set','box','roll','unit'] as $u)
                                        <option value="{{ $u }}" {{ old('unit_failed','pcs') === $u ? 'selected' : '' }}>{{ $u }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div style="font-size:11px;color:var(--text-dim);margin-top:5px;">
                                <i class="fas fa-ban" style="font-size:9px;"></i> {{ __('app.production_qc.ng_desc') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div style="margin-bottom:20px; padding:10px 15px; background:rgba(239,68,68,0.1); border-left:4px solid var(--danger); border-radius:4px;">
                    <span style="font-weight:600; font-size:14px;">{{ __('app.production_qc.total_ng_label') }} </span>
                    <span id="displayTotalNG" style="color:var(--danger); font-weight:800; font-size:18px;">{{ $productionQc->total_ng }}</span>
                </div>

                <div class="form-group" style="margin-top:14px;">
                    <label class="form-label">{{ __('app.production_qc.notes_label') }}</label>
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $productionQc->notes) }}</textarea>
                </div>
            </div>
        </div>

        <div style="display:flex;gap:10px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> {{ __('app.production_qc.update_btn') }}
            </button>
            <a href="{{ route('production-qc.index') }}" class="btn btn-ghost">
                {{ __('app.btn.cancel') }}
            </a>
        </div>
    </form>

    <script>
        function calculateTotalNG() {
            const ng = parseFloat(document.getElementById('qtyFailed').value) || 0;
            document.getElementById('displayTotalNG').innerText = ng.toFixed(2);
        }
        function syncUnit(sourceId, targetId) {
            document.getElementById(targetId).value = document.getElementById(sourceId).value;
        }
    </script>
</div>
@endsection
