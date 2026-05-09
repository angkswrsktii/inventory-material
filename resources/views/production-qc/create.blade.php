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
        <div class="page-subtitle">Catat hasil quality control dari produksi</div>
    </div>
</div>

@if(!$withdrawalCard)
<div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle"></i>
    Quality Control harus dibuat dari halaman <a href="{{ route('withdrawal-cards.index') }}" style="color:var(--accent);">Kartu Pengambilan</a>.
</div>
@else
<div style="max-width:760px;">
    <form action="{{ route('production-qc.store') }}" method="POST">
        @csrf
        <input type="hidden" name="withdrawal_card_id" value="{{ $withdrawalCard->id }}">

        {{-- Info Kartu Pengambilan --}}
        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-link" style="color:var(--accent);margin-right:8px;"></i>Dari Kartu Pengambilan</span>
                <span style="font-family:monospace;font-size:12px;color:var(--accent);">{{ $withdrawalCard->document_no }}</span>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
                    <div>
                        <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;">Part Name</div>
                        <div style="font-weight:600;margin-top:3px;">{{ $withdrawalCard->part_name }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;">Line / PIC</div>
                        <div style="margin-top:3px;color:var(--text-muted);">{{ $withdrawalCard->line }} · {{ $withdrawalCard->pic }}</div>
                    </div>
                    @if($withdrawalCard->work_order)
                    <div>
                        <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;">Work Order</div>
                        <div style="margin-top:3px;color:var(--text-muted);">{{ $withdrawalCard->work_order }}</div>
                    </div>
                    @endif
                    <div>
                        <div style="font-size:11px;color:var(--text-dim);text-transform:uppercase;">Tgl. Pengambilan</div>
                        <div style="margin-top:3px;color:var(--text-muted);">{{ $withdrawalCard->withdrawal_date->format('d M Y') }}</div>
                    </div>
                </div>

                {{-- Material yang diambil --}}
                <div style="font-size:12px;color:var(--text-dim);margin-bottom:8px;text-transform:uppercase;letter-spacing:.5px;">Material yang Diambil</div>
                <div style="background:var(--surface-2);border-radius:8px;overflow:hidden;">
                    @foreach($withdrawalCard->items as $item)
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 14px;border-bottom:1px solid var(--border);">
                        <div>
                            <span style="font-weight:600;font-size:13px;">{{ $item->material->name }}</span>
                            <span style="font-size:11px;color:var(--text-dim);margin-left:8px;">[{{ $item->material->code }}]</span>
                        </div>
                        <span style="color:var(--danger);font-weight:600;">{{ number_format($item->quantity, 2) }} {{ $item->material->unit }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Form QC --}}
        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-microscope" style="color:var(--accent-2);margin-right:8px;"></i>Hasil QC</span>
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
                    <label class="form-label">Qty Produksi <span class="required">*</span></label>
                    <input type="number" name="qty_produksi" id="qtyProduksi" class="form-control"
                           value="{{ old('qty_produksi', 0) }}" min="0" step="0.01" required oninput="hitungSisa()">
                    <div style="font-size:11px;color:var(--text-dim);margin-top:4px;">Total hasil produksi sebelum QC</div>
                </div>

                @if($errors->has('qty_sfg'))
                <div class="alert alert-danger" style="margin-bottom:12px;font-size:13px;">
                    <i class="fas fa-exclamation-circle"></i> {{ $errors->first('qty_sfg') }}
                </div>
                @endif

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" style="color:var(--success);">
                            <i class="fas fa-check-circle"></i> SFG — Barang Jadi <span class="required">*</span>
                        </label>
                        <input type="number" name="qty_sfg" id="qtySfg" class="form-control"
                               value="{{ old('qty_sfg', 0) }}" min="0" step="0.01" required oninput="hitungSisa()"
                               style="border-color:var(--success);">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" style="color:var(--danger);">
                            <i class="fas fa-times-circle"></i> NG — Barang Cacat <span class="required">*</span>
                        </label>
                        <input type="number" name="qty_ng" id="qtyNg" class="form-control"
                               value="{{ old('qty_ng', 0) }}" min="0" step="0.01" required oninput="hitungSisa()"
                               style="border-color:var(--danger);">
                    </div>
                </div>

                <div class="form-group" style="margin-top:14px;">
                    <label class="form-label">Keterangan NG <span style="font-size:11px;color:var(--text-dim);">(jenis cacat, penyebab, dll)</span></label>
                    <textarea name="ng_notes" class="form-control" rows="2"
                              placeholder="Contoh: Dimensi tidak sesuai, permukaan baret...">{{ old('ng_notes') }}</textarea>
                </div>

                {{-- Summary --}}
                <div id="summaryBar" style="display:none;margin-top:14px;padding:14px;
                     background:var(--surface-2);border-radius:10px;font-size:13px;">
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                        <span style="color:var(--text-muted);">Total SFG + NG</span>
                        <strong id="totalQty">0</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                        <span style="color:var(--text-muted);">% NG</span>
                        <strong id="pctNg" style="color:var(--danger);">0%</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:var(--text-muted);">Sisa tidak terkategori</span>
                        <strong id="sisaQty" style="color:var(--text-dim);">0</strong>
                    </div>
                    <div id="errorBar" style="display:none;margin-top:8px;color:var(--danger);font-size:12px;">
                        <i class="fas fa-exclamation-triangle"></i> Total melebihi Qty Produksi!
                    </div>
                </div>

                <div class="form-group" style="margin-top:14px;">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control" rows="2"
                              placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <div style="display:flex;gap:10px;">
            <button type="submit" class="btn btn-primary" id="submitBtn">
                <i class="fas fa-save"></i> Simpan QC
            </button>
            <a href="{{ route('withdrawal-cards.show', $withdrawalCard) }}" class="btn btn-ghost">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
    </form>
</div>
@endif
@endsection

@push('scripts')
<script>
function hitungSisa() {
    const prod = parseFloat(document.getElementById('qtyProduksi').value) || 0;
    const sfg  = parseFloat(document.getElementById('qtySfg').value)      || 0;
    const ng   = parseFloat(document.getElementById('qtyNg').value)        || 0;
    const total = sfg + ng;
    const sisa  = prod - total;
    const pct   = prod > 0 ? ((ng / prod) * 100).toFixed(2) : 0;

    document.getElementById('summaryBar').style.display = prod > 0 ? 'block' : 'none';
    document.getElementById('totalQty').textContent = total.toFixed(2);
    document.getElementById('sisaQty').textContent  = sisa.toFixed(2);
    document.getElementById('pctNg').textContent    = pct + '%';

    const over = total > prod;
    document.getElementById('errorBar').style.display = over ? 'block' : 'none';
    document.getElementById('submitBtn').disabled = over;
}
</script>
@endpush