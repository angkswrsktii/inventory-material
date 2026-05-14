@extends('layouts.app')
@section('title', 'Input Work Order')
@section('topbar-title', 'Work Order (Quality Check)')

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

                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px; margin-bottom: 20px; background:var(--surface-2); padding:15px; border-radius:8px;">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" style="color:var(--success);">
                            <i class="fas fa-check-circle"></i> OK (Good Part) <span class="required">*</span>
                        </label>
                        <input type="number" name="quantity_passed" id="qtyPassed" class="form-control" value="{{ old('quantity_passed', 0) }}" min="0" step="0.01" required style="border-color:var(--success); font-size: 16px; font-weight: bold;">
                        <div style="font-size:11px;color:var(--text-dim);margin-top:4px;">Menambah stok Part</div>
                    </div>
                    
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" style="color:var(--danger);">
                            <i class="fas fa-times-circle"></i> NG (Buang/Scrap) <span class="required">*</span>
                        </label>
                        <input type="number" name="quantity_failed" id="qtyFailed" class="form-control" value="{{ old('quantity_failed', 0) }}" min="0" step="0.01" required oninput="calculateTotalNG()" style="border-color:var(--danger); font-size: 16px; font-weight: bold;">
                        <div style="font-size:11px;color:var(--text-dim);margin-top:4px;">NG yang tidak bisa dipakai</div>
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" style="color:var(--warning);">
                            <i class="fas fa-recycle"></i> NG (Jadi Material) <span class="required">*</span>
                        </label>
                        <input type="number" name="quantity_failed_retur" id="qtyRetur" class="form-control" value="{{ old('quantity_failed_retur', 0) }}" min="0" step="0.01" required oninput="calculateTotalNG()" style="border-color:var(--warning); font-size: 16px; font-weight: bold;">
                        <div style="font-size:11px;color:var(--text-dim);margin-top:4px;">Sisa NG untuk diretur</div>
                    </div>
                </div>

                <div style="margin-bottom:20px; padding:10px 15px; background:rgba(239, 68, 68, 0.1); border-left:4px solid var(--danger); border-radius:4px;">
                    <span style="font-weight:600; font-size:14px;">Total Not Good (NG): </span>
                    <span id="displayTotalNG" style="color:var(--danger); font-weight:800; font-size:18px;">0</span>
                </div>

                <div class="form-group" style="margin-top:14px;">
                    <label class="form-label">Catatan / Keterangan</label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="Keterangan hasil WO atau alasan barang NG...">{{ old('notes') }}</textarea>
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
            let ng2 = parseFloat(document.getElementById('qtyRetur').value) || 0;
            document.getElementById('displayTotalNG').innerText = (ng1 + ng2).toFixed(2);
        }
        // Initialize on load
        window.onload = calculateTotalNG;
    </script>
    @endif
</div>
@endsection