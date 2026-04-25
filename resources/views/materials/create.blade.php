@extends('layouts.app')

@section('title', 'Tambah Material')
@section('topbar-title', 'Tambah Material')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('materials.index') }}">Data Material</a>
    <span class="sep">/</span>
    <span>Tambah Material</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">Tambah Material Baru</div>
        <div class="page-subtitle">Isi form berikut untuk mendaftarkan raw material baru</div>
    </div>
</div>

<div style="max-width: 720px;">
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-cube" style="color:var(--accent); margin-right:8px;"></i>Informasi Material</span>
        </div>
        <div class="card-body">
            <form action="{{ route('materials.store') }}" method="POST">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Kode Barang <span class="required">*</span></label>
                        <input type="text" name="code" class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}"
                               value="{{ old('code') }}" placeholder="Contoh: RM-001" required>
                        @error('code') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Satuan <span class="required">*</span></label>
                        <input type="text" name="unit" class="form-control {{ $errors->has('unit') ? 'is-invalid' : '' }}"
                               value="{{ old('unit') }}" placeholder="Contoh: Kg, Pcs, Ltr" required>
                        @error('unit') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Material <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                           value="{{ old('name') }}" placeholder="Masukkan nama material" required>
                    @error('name') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Spesifikasi</label>
                    <input type="text" name="specification" class="form-control"
                           value="{{ old('specification') }}" placeholder="Contoh: 304 SS, Grade A, dll.">
                    @error('specification') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Supplier / Vendor</label>
                    <input type="text" name="supplier" class="form-control"
                           value="{{ old('supplier') }}" placeholder="Nama supplier">
                    @error('supplier') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Stok Minimum <span class="required">*</span></label>
                        <input type="number" name="minimum_stock" class="form-control {{ $errors->has('minimum_stock') ? 'is-invalid' : '' }}"
                               value="{{ old('minimum_stock', 0) }}" min="0" step="0.01"
                               placeholder="0">
                        <div style="font-size:11px; color:var(--text-muted); margin-top:4px;">
                            Alert akan muncul jika stok di bawah nilai ini
                        </div>
                        @error('minimum_stock') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Keterangan</label>
                    <textarea name="description" class="form-control" placeholder="Catatan tambahan...">{{ old('description') }}</textarea>
                </div>

                <div class="divider"></div>

                <div style="display:flex; gap:10px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Material
                    </button>
                    <a href="{{ route('materials.index') }}" class="btn btn-ghost">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection