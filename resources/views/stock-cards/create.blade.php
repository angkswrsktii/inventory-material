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
                        <label class="form-label">No. Referensi / PO</label>
                        <input type="text" name="reference_no" class="form-control"
                               value="{{ old('reference_no') }}" placeholder="Contoh: PO-2024-001">
                        @error('reference_no') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Sumber / Keterangan</label>
                    <input type="text" name="source" class="form-control"
                           value="{{ old('source') }}" placeholder="Nama supplier atau keterangan transaksi">
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
@endsection

@push('scripts')
<script>
document.getElementById('materialSelect').addEventListener('change', function () {
    const opt = this.options[this.selectedIndex];
    const stock = opt.dataset.stock;
    const unit = opt.dataset.unit;
    const info = document.getElementById('stockInfo');
    if (this.value) {
        document.getElementById('stockValue').textContent = parseFloat(stock).toFixed(2) + ' ' + unit;
        info.style.display = 'block';
    } else {
        info.style.display = 'none';
    }
});
</script>
@endpush