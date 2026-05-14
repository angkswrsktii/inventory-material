@extends('layouts.app')
@section('title', 'Input Goods Adjustment')
@section('topbar-title', 'Goods Adjustment')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('goods-adjustment.index') }}">Goods Adjustment</a>
    <span class="sep">/</span>
    <span>Input Adjustment Baru</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">Buat Goods Adjustment</div>
        <div class="page-subtitle">Sesuaikan stok fisik secara manual (Otomatis masuk Mutasi)</div>
    </div>
</div>

<div style="max-width:700px;">
    <form action="{{ route('goods-adjustment.store') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-sliders-h" style="color:var(--accent);margin-right:8px;"></i>Form Adjustment Stok</span>
            </div>
            <div class="card-body">
                
                <div class="form-group">
                    <label class="form-label">Gudang / Warehouse <span class="required">*</span></label>
                    <select name="m_warehouse_id" class="form-control" required>
                        <option value="">-- Pilih Gudang --</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ old('m_warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                {{ $warehouse->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Pilih Material <span class="required">*</span></label>
                    <select name="m_material_id" class="form-control" required>
                        <option value="">-- Pilih Material --</option>
                        @foreach($materials as $material)
                            <option value="{{ $material->id }}" {{ old('m_material_id') == $material->id ? 'selected' : '' }}>
                                [{{ $material->code }}] {{ $material->name }} - {{ $material->specification }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="background:var(--surface-2); padding:16px; border-radius:8px;">
                    <label class="form-label">Tipe Adjustment <span class="required">*</span></label>
                    <div style="display:flex; gap:20px; margin-top:8px;">
                        <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-weight:600; color:var(--success);">
                            <input type="radio" name="type" value="in" {{ old('type') == 'in' ? 'checked' : '' }} required> 
                            <i class="fas fa-arrow-down"></i> Masuk (Goods Receipt)
                        </label>
                        <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-weight:600; color:var(--danger);">
                            <input type="radio" name="type" value="out" {{ old('type') == 'out' ? 'checked' : '' }} required> 
                            <i class="fas fa-arrow-up"></i> Keluar (Goods Issue)
                        </label>
                    </div>
                    <div style="font-size:12px; color:var(--text-muted); margin-top:10px;">
                        * Pilih <b>Masuk</b> untuk menambah stok, atau <b>Keluar</b> untuk mengurangi stok.
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Quantity / Jumlah Stok <span class="required">*</span></label>
                    <input type="number" name="quantity" class="form-control" value="{{ old('quantity') }}" min="0.01" step="0.01" required style="font-size: 16px; font-weight: bold; max-width:200px;">
                </div>

                <div class="form-group">
                    <label class="form-label">Alasan / Keterangan <span class="required">*</span></label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="Contoh: Stok opname, selisih hitung fisik, barang rusak, dll..." required>{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <div style="display:flex; gap:10px; margin-top:20px;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Adjustment & Mutasi</button>
            <a href="{{ route('goods-adjustment.index') }}" class="btn btn-ghost">Batal</a>
        </div>
    </form>
</div>
@endsection