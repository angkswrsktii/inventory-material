@extends('layouts.app')
@section('title', 'Edit Material')
@section('topbar-title', 'Edit Material')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('materials.index') }}">Data Material</a>
    <span class="sep">/</span>
    <span>Edit: {{ $material->name }}</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">Edit Material</div>
        <div class="page-subtitle">Perbarui data material</div>
    </div>
</div>

<div style="max-width:860px;">
    <form action="{{ route('materials.update', $material) }}" method="POST">
        @csrf @method('PUT')

        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-tag" style="color:var(--accent);margin-right:8px;"></i>Informasi Part</span>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Kode Barang <span class="required">*</span></label>
                        <input type="text" name="code" class="form-control" value="{{ old('code', $material->code) }}" required>
                        @error('code') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Part No.</label>
                        <input type="text" name="part_no" class="form-control" value="{{ old('part_no', $material->part_no) }}" placeholder="2PV-F1585-00">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Part Name <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $material->name) }}" required>
                    @error('name') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Customer</label>
                        <input type="text" name="customer" class="form-control" value="{{ old('customer', $material->customer) }}" placeholder="PT. BME">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Satuan <span class="required">*</span></label>
                        <select name="unit" class="form-control" required>
                            <option value="">-- Pilih Satuan --</option>
                            @php $units = ['Pcs','Kg','Gram','Ltr','mL','Meter','cm','mm','Roll','Lembar','Dus','Karton','Lusin','Set','Unit','Pasang','Botol','Kantong','Sak','Batang']; @endphp
                            @foreach($units as $u)
                                <option value="{{ $u }}" {{ old('unit', $material->unit) === $u ? 'selected' : '' }}>{{ $u }}</option>
                            @endforeach
                        </select>
                        @error('unit') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-cube" style="color:var(--accent-2);margin-right:8px;"></i>Informasi Material</span>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Material / Spesifikasi</label>
                    <input type="text" name="specification" class="form-control" value="{{ old('specification', $material->specification) }}" placeholder="Ø15.9 x Ø1.6 x 3000">
                </div>
                <div class="form-group">
                    <label class="form-label">Supplier / Vendor</label>
                    <select name="supplier" class="form-control">
                        <option value="">-- Pilih Supplier (opsional) --</option>
                        @foreach($suppliers ?? [] as $s)
                            <option value="{{ $s->name }}" {{ old('supplier', $material->supplier) == $s->name ? 'selected' : '' }}>
                                {{ $s->name }}{{ $s->phone ? ' — '.$s->phone : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Panjang Material <span style="font-size:11px;color:var(--text-dim);">(mm)</span></label>
                        <input type="number" name="panjang_material" id="panjangMat" class="form-control"
                               value="{{ old('panjang_material', $material->panjang_material) }}" step="0.01" min="0" oninput="hitungBQ()">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Panjang Part <span style="font-size:11px;color:var(--text-dim);">(mm)</span></label>
                        <input type="number" name="panjang_part" id="panjangPart" class="form-control"
                               value="{{ old('panjang_part', $material->panjang_part) }}" step="0.01" min="0" oninput="hitungBQ()">
                    </div>
                    <div class="form-group">
                        <label class="form-label">B/Q</label>
                        <input type="number" name="bq" id="bqField" class="form-control"
                               value="{{ old('bq', $material->bq) }}" step="0.0001" min="0"
                               style="background:var(--surface-2);">
                        <div style="font-size:11px;color:var(--text-dim);margin-top:4px;">= Panjang Material ÷ (Panjang Part + 3)</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-boxes-stacked" style="color:var(--warning);margin-right:8px;"></i>Informasi Stok</span>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Stok Minimum</label>
                        <input type="number" name="minimum_stock" class="form-control" value="{{ old('minimum_stock', $material->minimum_stock) }}" min="0" step="0.01">
                        <div style="font-size:11px;color:var(--text-muted);margin-top:4px;">Alert muncul jika stok ≤ nilai ini</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Stok Maksimum</label>
                        <input type="number" name="max_stock" class="form-control" value="{{ old('max_stock', $material->max_stock) }}" min="0" step="0.01" placeholder="Opsional">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Keterangan</label>
                    <textarea name="description" class="form-control" rows="2">{{ old('description', $material->description) }}</textarea>
                </div>
                <div class="form-group">
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $material->is_active) ? 'checked' : '' }}>
                        <span class="form-label" style="margin:0;">Material Aktif</span>
                    </label>
                </div>
            </div>
        </div>

        <div style="display:flex; gap:10px;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
            <a href="{{ route('materials.index') }}" class="btn btn-ghost"><i class="fas fa-times"></i> Batal</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function hitungBQ() {
    const pm = parseFloat(document.getElementById('panjangMat')?.value || 0);
    const pp = parseFloat(document.getElementById('panjangPart')?.value || 0);
    const bq = document.getElementById('bqField');
    if (pm > 0 && pp > 0) bq.value = (pm / (pp + 3)).toFixed(4);
}
</script>
@endpush