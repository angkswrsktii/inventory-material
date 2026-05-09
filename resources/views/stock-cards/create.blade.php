@extends('layouts.app')
@section('title', 'Input Quality Control Produksi')
@section('topbar-title', 'Quality Control Hasil Produksi')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('production-qc.index') }}">Quality Control Produksi</a>
    <span class="sep">/</span>
    <span>Input Quality Control Baru</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">Input Quality Control Hasil Produksi</div>
        <div class="page-subtitle">Catat hasil quality control barang produksi</div>
    </div>
</div>

<div style="max-width:760px;">
    <form action="{{ route('production-qc.store') }}" method="POST">
        @csrf

        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-file-alt" style="color:var(--accent);margin-right:8px;"></i>Informasi QC</span>
                <span style="font-size:12px;color:var(--text-dim);font-family:monospace;">{{ $documentNo }}</span>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tanggal Quality Control <span class="required">*</span></label>
                        <input type="date" name="qc_date" class="form-control"
                               value="{{ old('qc_date', date('Y-m-d')) }}" required>
                        @error('qc_date') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Gedung</label>
                        <select name="gedung" class="form-control">
                            <option value="">-- Pilih Gedung --</option>
                            @foreach($gedungList as $g)
                                <option value="{{ $g }}" {{ old('gedung') === $g ? 'selected' : '' }}>{{ $g }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Material <span class="required">*</span></label>
                    <select name="material_id" class="form-control" required>
                        <option value="">-- Pilih Material --</option>
                        @foreach($materials as $m)
                            <option value="{{ $m->id }}" {{ old('material_id') == $m->id ? 'selected' : '' }}>
                                [{{ $m->code }}] {{ $m->name }}
                                @if($m->part_no) ({{ $m->part_no }}) @endif
                            </option>
                        @endforeach
                    </select>
                    @error('material_id') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-microscope" style="color:var(--accent-2);margin-right:8px;"></i>Hasil QC</span>
            </div>
            <div class="card-body">
                @if($errors->has('qty_sfg'))
                <div class="alert alert-danger" style="margin-bottom:16px;font-size:13px;">
                    <i class="fas fa-exclamation-circle"></i> {{ $errors->first('qty_sfg') }}
                </div>
                @endif

                <div class="form-group">
                    <label class="form-label">Qty Produksi <span class="required">*</span></label>
                    <input type="number" name="qty_produksi" id="qtyProduksi" class="form-control"
                           value="{{ old('qty_produksi', 0) }}" min="0" step="0.01" required oninput="hitungSisa()">
                    <div style="font-size:11px;color:var(--text-dim);margin-top:4px;">Total hasil produksi sebelum QC</div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" style="color:var(--success);">
                            <i class="fas fa-check-circle"></i> SFG (Barang Jadi) <span class="required">*</span>
                        </label>
                        <input type="number" name="qty_sfg" id="qtySfg" class="form-control"
                               value="{{ old('qty_sfg', 0) }}" min="0" step="0.01" required oninput="hitungSisa()">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" style="color:var(--danger);">
                            <i class="fas fa-times-circle"></i> NG (Barang Cacat) <span class="required">*</span>
                        </label>
                        <input type="number" name="qty_ng" id="qtyNg" class="form-control"
                               value="{{ old('qty_ng', 0) }}" min="0" step="0.01" required oninput="hitungSisa()">
                    </div>

                </div>

                {{-- Summary bar --}}
                <div id="summaryBar" style="margin-top:16px;padding:14px;background:var(--surface-2);
                     border-radius:10px;font-size:13px;display:none;">
                    <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                        <span style="color:var(--text-muted);">Total SFG + NG + Retur</span>
                        <strong id="totalQty" style="color:var(--text);">0</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                        <span style="color:var(--text-muted);">% NG</span>
                        <strong id="pctNg" style="color:var(--danger);">0%</strong>
                    </div>
                    <div id="sisaRow" style="display:flex;justify-content:space-between;">
                        <span style="color:var(--text-muted);">Sisa tidak terkategori</span>
                        <strong id="sisaQty" style="color:var(--text-dim);">0</strong>
                    </div>
                    <div id="errorBar" style="display:none;margin-top:8px;color:var(--danger);font-size:12px;">
                        <i class="fas fa-exclamation-triangle"></i> Total melebihi Qty Produksi!
                    </div>
                </div>

                <div class="form-group" style="margin-top:16px;">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control" rows="3"
                              placeholder="Keterangan tambahan hasil QC...">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <div style="display:flex;gap:10px;">
            <button type="submit" class="btn btn-primary" id="submitBtn">
                <i class="fas fa-save"></i> Simpan QC
            </button>
            <a href="{{ route('production-qc.index') }}" class="btn btn-ghost">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function hitungSisa() {
    const prod  = parseFloat(document.getElementById('qtyProduksi').value) || 0;
    const sfg   = parseFloat(document.getElementById('qtySfg').value)      || 0;
    const ng    = parseFloat(document.getElementById('qtyNg').value)        || 0;
    const total = sfg + ng;
    const sisa  = prod - total;
    const pct   = prod > 0 ? ((ng / prod) * 100).toFixed(2) : 0;

    document.getElementById('summaryBar').style.display = prod > 0 ? 'block' : 'none';
    document.getElementById('totalQty').textContent = total.toFixed(2);
    document.getElementById('sisaQty').textContent  = sisa.toFixed(2);
    document.getElementById('pctNg').textContent    = pct + '%';

    const errorBar = document.getElementById('errorBar');
    const submitBtn = document.getElementById('submitBtn');
    if (total > prod) {
        errorBar.style.display = 'block';
        submitBtn.disabled = true;
    } else {
        errorBar.style.display = 'none';
        submitBtn.disabled = false;
    }
}
</script>
@endpush