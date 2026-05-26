@extends('layouts.app')
@section('title', 'Input Work Order')
@section('topbar-title', __('app.nav.work_order') . ' — ' . __('app.nav.quality_check'))

@section('content')
<div class="breadcrumb">
    <a href="{{ route('production-qc.index') }}">Work Order</a>
    <span class="sep">/</span>
    <span>Input WO Baru</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">Input Work Order (QC)</div>
        <div class="page-subtitle">Catat hasil produksi dari Good Issue</div>
    </div>
</div>

<div style="max-width:760px;">
    {{-- Form Pilih GI --}}
    <div class="card" style="margin-bottom:20px;">
        <div class="card-body">
            <form method="GET" action="{{ route('production-qc.create') }}">
                <label class="form-label" style="font-weight:600;">Pilih Good Issue <span class="required">*</span></label>
                <div style="display:flex; gap:10px;">
                    <select name="t_good_issue_id" class="form-control" onchange="this.form.submit()" required>
                        <option value="">-- Silakan Pilih Good Issue (Hanya yang belum di-WO) --</option>
                        @foreach($availableGIs as $gi)
                            <option value="{{ $gi->id }}" {{ request('t_good_issue_id') == $gi->id ? 'selected' : '' }}>
                                {{ $gi->gi_number }} | Target: {{ $gi->part->part_name ?? '-' }} | PIC: {{ $gi->pic->name ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    @if($goodIssue)
    <form action="{{ route('production-qc.store') }}" method="POST">
        @csrf
        <input type="hidden" name="t_good_issue_id" value="{{ $goodIssue->id }}">

        {{-- Info Good Issue Terpilih --}}
        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-link" style="color:var(--accent);margin-right:8px;"></i>Dari Good Issue</span>
                <span style="font-family:monospace;font-size:12px;color:var(--accent);">{{ $goodIssue->gi_number }}</span>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
                    <div>
                        <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;">Target Produksi (Part)</div>
                        <div style="font-weight:600;margin-top:3px;">{{ $goodIssue->part->part_name ?? '-' }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;">PIC Pemotong</div>
                        <div style="margin-top:3px;color:var(--text-muted);">{{ $goodIssue->pic->name ?? '-' }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;">Tujuan / Catatan</div>
                        <div style="margin-top:3px;color:var(--text-muted);">{{ $goodIssue->purpose }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;">Tgl. Keluar GI</div>
                        <div style="margin-top:3px;color:var(--text-muted);">{{ $goodIssue->issue_date->format('d M Y') }}</div>
                    </div>
                </div>

                {{-- Material yang dikeluarkan --}}
                <div style="font-size:12px;color:var(--text-dim);margin-bottom:8px;text-transform:uppercase;letter-spacing:.5px;">Material yang Dikeluarkan</div>
                <div style="background:var(--surface-2);border-radius:8px;overflow:hidden;">
                    @foreach($goodIssue->items as $item)
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 14px;border-bottom:1px solid var(--border);">
                        <div>
                            <span style="font-weight:600;font-size:13px;">{{ $item->material->name ?? '-' }}</span>
                            <span style="font-size:11px;color:var(--text-dim);margin-left:8px;">[{{ $item->material->code ?? '-' }}]</span>
                        </div>
                        <span style="color:var(--danger);font-weight:600;">{{ number_format($item->quantity, 2) }} {{ $item->unit ?? 'Pcs' }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Form Hasil QC --}}
        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-clipboard-check" style="color:var(--accent-2);margin-right:8px;"></i>Input Hasil Work Order (QC)</span>
                <span style="font-size:12px;color:var(--text-dim);font-family:monospace;">{{ $woNumber }}</span>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Tanggal Work Order <span class="required">*</span></label>
                    <input type="date" name="qc_date" class="form-control" style="width: 200px;" value="{{ old('qc_date', date('Y-m-d')) }}" required>
                    @error('qc_date') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                {{-- Quantity Grid --}}
                <div style="background:var(--surface-2); padding:18px; border-radius:10px; margin-bottom:20px;">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:14px;">

                        {{-- OK (Good Part) --}}
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="color:var(--success); font-weight:600;">
                                <i class="fas fa-check-circle"></i> OK (Good Part) <span class="required">*</span>
                            </label>
                            <div style="display:flex; gap:8px; align-items:center;">
                                <input type="number" name="quantity_passed" id="qtyPassed"
                                       class="form-control"
                                       value="{{ old('quantity_passed', 0) }}"
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
                                <i class="fas fa-arrow-up" style="font-size:9px;"></i> Menambah stok Part
                            </div>
                        </div>

                        {{-- NG (Buang/Scrap) --}}
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="color:var(--danger); font-weight:600;">
                                <i class="fas fa-times-circle"></i> NG (Buang/Scrap) <span class="required">*</span>
                            </label>
                            <div style="display:flex; gap:8px; align-items:center;">
                                <input type="number" name="quantity_failed" id="qtyFailed"
                                       class="form-control"
                                       value="{{ old('quantity_failed', 0) }}"
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
                                <i class="fas fa-ban" style="font-size:9px;"></i> NG yang tidak bisa dipakai
                            </div>
                        </div>

                    </div>
                </div>

                <div style="margin-bottom:20px; padding:10px 15px; background:rgba(239, 68, 68, 0.1); border-left:4px solid var(--danger); border-radius:4px;">
                    <span style="font-weight:600; font-size:14px;">Total Not Good (NG): </span>
                    <span id="displayTotalNG" style="color:var(--danger); font-weight:800; font-size:18px;">0</span>
                </div>

                <div class="form-group" style="margin-top:14px;">
                    <label class="form-label">Catatan / Keterangan</label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="Keterangan hasil WO atau alasan Material NG...">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <div style="display:flex;gap:10px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Draft
            </button>
            <a href="{{ route('production-qc.index') }}" class="btn btn-ghost">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
    </form>

    <script>
        function calculateTotalNG() {
            let ng1 = parseFloat(document.getElementById('qtyFailed').value) || 0;
            document.getElementById('displayTotalNG').innerText = ng1.toFixed(2);
        }

        function syncUnit(sourceId, targetId) {
            document.getElementById(targetId).value = document.getElementById(sourceId).value;
        }

        // Initialize on load
        window.onload = calculateTotalNG;
    </script>
    @endif
</div>
@endsection