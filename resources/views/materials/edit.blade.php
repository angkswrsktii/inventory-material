@extends('layouts.app')

@section('title', 'Edit Material')
@section('topbar-title', 'Edit Material')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('materials.index') }}">Data Material</a>
    <span class="sep">/</span>
    <span>Edit</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">Edit Material</div>
        <div class="page-subtitle">Perbarui informasi: <strong style="color:var(--accent)">{{ $material->name }}</strong></div>
    </div>
</div>

<div style="max-width: 720px;">
    <div class="card">
        <div class="card-header">
            <span class="card-title">
                <i class="fas fa-pen" style="color:var(--accent);margin-right:8px;"></i>Edit Informasi Material
            </span>
            <span class="mono" style="color:var(--accent); font-size:13px;">{{ $material->code }}</span>
        </div>
        <div class="card-body">
            <form action="{{ route('materials.update', $material) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Kode Barang <span class="required">*</span></label>
                        <input type="text" name="code" class="form-control"
                               value="{{ old('code', $material->code) }}" required>
                        @error('code') <div class="form-error">{{ $message }}</div> @enderror
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

                <div class="form-group">
                    <label class="form-label">Nama Material <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name', $material->name) }}" required>
                    @error('name') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Spesifikasi</label>
                    <input type="text" name="specification" class="form-control"
                           value="{{ old('specification', $material->specification) }}"
                           placeholder="Contoh: 304 SS, Grade A, dll.">
                    @error('specification') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Supplier / Vendor</label>
                    <select name="supplier" class="form-control">
                        <option value="">-- Pilih Supplier (opsional) --</option>
                        @foreach($suppliers ?? [] as $s)
                            <option value="{{ $s->name }}"
                                {{ old('supplier', $material->supplier) == $s->name ? 'selected' : '' }}>
                                {{ $s->name }}{{ $s->phone ? ' — '.$s->phone : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Stok Minimum <span class="required">*</span></label>
                        <input type="number" name="minimum_stock" class="form-control"
                               value="{{ old('minimum_stock', $material->minimum_stock) }}"
                               min="0" step="0.01" required>
                        <div style="font-size:11px; color:var(--text-muted); margin-top:4px;">
                            Alert muncul jika stok di bawah nilai ini
                        </div>
                        @error('minimum_stock') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Stok Saat Ini</label>
                        <input type="text" class="form-control"
                               value="{{ number_format($material->current_stock, 2) }} {{ $material->unit }}"
                               disabled style="opacity:0.5; cursor:not-allowed;">
                        <div style="font-size:11px; color:var(--text-muted); margin-top:4px;">
                            Ubah stok lewat menu Kartu Stok
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Keterangan</label>
                    <textarea name="description" class="form-control"
                              placeholder="Catatan tambahan...">{{ old('description', $material->description) }}</textarea>
                </div>

                <div class="divider"></div>

                <div style="display:flex; gap:10px; flex-wrap:wrap;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('materials.index') }}" class="btn btn-ghost">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <a href="{{ route('stock-cards.show', $material) }}" class="btn btn-ghost" style="margin-left:auto;">
                        <i class="fas fa-table-list"></i> Lihat Kartu Stok
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection