@extends('layouts.app')
@section('title', 'Tambah Material')
@section('topbar-title', __('app.nav.master_data') . ' — ' . __('app.nav.data_material'))

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

<div style="max-width:860px;">
    <form action="{{ route('materials.store') }}" method="POST">
        @csrf

        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-cube" style="color:var(--accent-2);margin-right:8px;"></i>Informasi Material</span>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Kode Material <span class="required">*</span></label>
                        <input type="text" name="code" class="form-control" value="{{ old('code') }}" placeholder="MAT-001" required>
                        @error('code') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Material <span class="required">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Nama material mentah" required>
                        @error('name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Supplier / Vendor</label>
                        <select name="m_supplier_id" class="form-control">
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($suppliers ?? [] as $s)
                                <option value="{{ $s->id }}" {{ old('m_supplier_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->name }}{{ $s->phone ? ' — '.$s->phone : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('m_supplier_id') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Project Type</label>
                        <select name="project_id" class="form-control">
                            <option value="">-- Pilih Project --</option>
                            @foreach($projects ?? [] as $mt)
                                <option value="{{ $mt->id }}" {{ old('project_id') == $mt->id ? 'selected' : '' }}>
                                    {{ $mt->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Satuan <span class="required">*</span></label>
                        <select name="unit" class="form-control" required>
                            <option value="">-- Pilih Satuan --</option>
                            @php $units = ['Pcs','Kg','Gram','Ltr','mL','Meter','cm','mm','Roll','Lembar','Dus','Karton','Lusin','Set','Unit','Pasang','Botol','Kantong','Sak','Batang']; @endphp
                            @foreach($units as $u)
                                <option value="{{ $u }}" {{ old('unit','Pcs') === $u ? 'selected' : '' }}>{{ $u }}</option>
                            @endforeach
                        </select>
                        @error('unit') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Material / Spesifikasi</label>
                        <input type="text" name="specification" class="form-control" value="{{ old('specification') }}" placeholder="Contoh: Ø15.9 x Ø1.6 x 3000">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Panjang Material <span style="font-size:11px;color:var(--text-dim);">(mm)</span></label>
                        <input type="number" name="panjang_material" class="form-control" value="{{ old('panjang_material') }}" step="0.01" min="0" placeholder="3000">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Keterangan</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="Catatan tambahan...">{{ old('description') }}</textarea>
                </div>
                <div class="form-group">
                    <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                        <input type="checkbox" name="is_active" value="1" checked style="width:16px;height:16px;">
                        <span style="font-weight:500;">Status Aktif</span>
                    </label>
                </div>
            </div>
        </div>

        <div style="display:flex; gap:10px;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Material</button>
            <a href="{{ route('materials.index') }}" class="btn btn-ghost"><i class="fas fa-times"></i> Batal</a>
        </div>
    </form>
</div>
@endsection