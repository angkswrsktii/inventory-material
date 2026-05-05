@extends('layouts.app')
@section('title', 'Input Penerimaan Barang')
@section('topbar-title', 'Penerimaan Barang')
@section('topbar-actions')
    <span style="font-size:12px; color: var(--text-muted);">
        <i class="fas fa-clock"></i>
        {{ now()->format('d M Y, H:i') }}
    </span>
@endsection

@section('content')
<div class="breadcrumb">
    <a href="{{ route('stock-cards.index') }}">Kartu Stok</a>
    <span class="sep">/</span>
    <span>Input Penerimaan</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">Input Penerimaan Barang</div>
        <div class="page-subtitle">Catat barang masuk dari supplier ke kartu stok</div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success" style="margin-bottom:16px;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

{{-- ── MODE: DARI PO (multi-item) ── --}}
@if(!empty($poItems) && count($poItems) > 0)
<div style="max-width:860px;">
    @if($fromPo)
    <div class="alert alert-success" style="margin-bottom:16px;">
        <i class="fas fa-link"></i>
        Penerimaan dari PO <strong>{{ $fromPo }}</strong> — Silakan verifikasi dan simpan tiap item ke kartu stok.
    </div>
    @endif

    @foreach($poItems as $idx => $poItem)
    @php $mat = $materials->firstWhere('id', $poItem['material_id']); @endphp
    @if(!$mat || $poItem['qty'] <= 0) @continue @endif
    <div class="card" style="margin-bottom:16px;">
        <div class="card-header">
            <span class="card-title">
                <i class="fas fa-cube" style="color:var(--accent);margin-right:8px;"></i>
                {{ $mat->name }}
                <span style="font-size:12px;color:var(--text-muted);margin-left:8px;">[{{ $mat->code }}]</span>
            </span>
            <span class="badge badge-success">Qty diterima: {{ $poItem['qty'] }} {{ $mat->unit }}</span>
        </div>
        <div class="card-body">
            <form action="{{ route('stock-cards.store') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="in">
                <input type="hidden" name="material_id" value="{{ $mat->id }}">
                <input type="hidden" name="quantity" value="{{ $poItem['qty'] }}">
                <input type="hidden" name="reference_no" value="{{ $fromPo }}">
                <input type="hidden" name="source" value="{{ $supplierName }}">

                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Tanggal Penerimaan <span class="required">*</span></label>
                        <input type="date" name="transaction_date" class="form-control"
                               value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Jumlah Diterima</label>
                        <input type="number" name="quantity" class="form-control"
                               value="{{ $poItem['qty'] }}" min="0.01" step="0.01" required>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Stok Saat Ini</label>
                        <input type="text" class="form-control" value="{{ number_format($mat->current_stock,2) }} {{ $mat->unit }}" disabled
                               style="opacity:0.7;">
                    </div>
                </div>

                <div class="form-group" style="margin-top:12px;margin-bottom:0;">
                    <label class="form-label">Catatan</label>
                    <input type="text" name="notes" class="form-control"
                           placeholder="Catatan tambahan (opsional)" value="{{ $fromPo }}">
                </div>

                <div style="margin-top:14px;">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan ke Kartu Stok
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endforeach

</div>

{{-- ── MODE: MANUAL (form biasa) ── --}}
@else
<div style="max-width:720px;">
    <div class="card">
        <div class="card-header">
            <span class="card-title">
                <i class="fas fa-arrow-right-to-bracket" style="color:var(--success);margin-right:8px;"></i>
                Form Penerimaan Barang
            </span>
        </div>
        <div class="card-body">
            <form action="{{ route('stock-cards.store') }}" method="POST">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tanggal Penerimaan <span class="required">*</span></label>
                        <input type="date" name="transaction_date" class="form-control"
                               value="{{ old('transaction_date', date('Y-m-d')) }}" required>
                        @error('transaction_date') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tipe Transaksi <span class="required">*</span></label>
                        <select name="type" class="form-control" required id="typeSelect">
                            <option value="in"  {{ old('type','in') === 'in'  ? 'selected' : '' }}>📥 Masuk (dari Supplier)</option>
                            <option value="out" {{ old('type')      === 'out' ? 'selected' : '' }}>📤 Keluar (penyesuaian)</option>
                        </select>
                        @error('type') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Material <span class="required">*</span></label>
                    <select name="material_id" class="form-control" required id="materialSelect">
                        <option value="">-- Pilih Material --</option>
                        @foreach($materials as $m)
                            <option value="{{ $m->id }}"
                                    data-stock="{{ $m->current_stock }}"
                                    data-unit="{{ $m->unit }}"
                                    {{ old('material_id') == $m->id ? 'selected' : '' }}>
                                [{{ $m->code }}] {{ $m->name }} — Stok: {{ number_format($m->current_stock,2) }} {{ $m->unit }}
                            </option>
                        @endforeach
                    </select>
                    @error('material_id') <div class="form-error">{{ $message }}</div> @enderror
                    <div id="stockInfo" style="display:none; margin-top:8px; padding:10px 14px;
                         background:var(--surface-2); border-radius:8px; font-size:12.5px; color:var(--text-muted);">
                        Stok saat ini: <strong id="stockValue" style="color:var(--text);"></strong>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Jumlah <span class="required">*</span></label>
                        <input type="number" name="quantity" class="form-control"
                               value="{{ old('quantity') }}" min="0.01" step="0.01"
                               placeholder="0.00" required>
                        @error('quantity') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. Purchase Order</label>
                        <select name="reference_no" class="form-control" id="poSelect"
                                onchange="fillSupplierFromPO(this)">
                            <option value="">-- Pilih PO (opsional) --</option>
                            @foreach($purchaseOrders ?? [] as $po)
                                <option value="{{ $po->document_no }}"
                                        data-supplier="{{ $po->supplier?->name ?? $po->supplier_name }}"
                                        {{ old('reference_no') == $po->document_no ? 'selected' : '' }}>
                                    {{ $po->document_no }}{{ ($po->supplier?->name ?? $po->supplier_name) ? ' — '.($po->supplier?->name ?? $po->supplier_name) : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('reference_no') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Supplier / Vendor</label>
                    <select name="source" class="form-control" id="supplierSelect">
                        <option value="">-- Pilih Supplier (opsional) --</option>
                        @foreach($suppliers ?? [] as $s)
                            <option value="{{ $s->name }}" {{ old('source') == $s->name ? 'selected' : '' }}>
                                {{ $s->name }}{{ $s->phone ? ' — '.$s->phone : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('source') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control"
                              placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
                </div>

                <div class="divider"></div>

                <div style="display:flex; gap:10px;">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan Transaksi
                    </button>
                    <a href="{{ route('stock-cards.index') }}" class="btn btn-ghost">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
function fillSupplierFromPO(sel) {
    const opt = sel.options[sel.selectedIndex];
    const supplierSelect = document.getElementById('supplierSelect');
    if (supplierSelect && opt.dataset.supplier) {
        // Cari option yang cocok dengan supplier name
        for (let i = 0; i < supplierSelect.options.length; i++) {
            if (supplierSelect.options[i].value === opt.dataset.supplier) {
                supplierSelect.selectedIndex = i;
                break;
            }
        }
    }
}

const matSelect = document.getElementById('materialSelect');
if (matSelect) {
    matSelect.addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        const info = document.getElementById('stockInfo');
        if (this.value) {
            document.getElementById('stockValue').textContent =
                parseFloat(opt.dataset.stock).toFixed(2) + ' ' + opt.dataset.unit;
            info.style.display = 'block';
        } else {
            info.style.display = 'none';
        }
    });
}
</script>
@endpush