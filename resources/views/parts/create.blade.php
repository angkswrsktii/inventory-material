@extends('layouts.app')
@section('title', 'Tambah Part')
@section('topbar-title', 'Tambah Part')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('parts.index') }}">Data Part</a>
    <span class="sep">/</span>
    <span>Tambah Part</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">Tambah Part Baru</div>
        <div class="page-subtitle">Isi form berikut untuk mendaftarkan part baru</div>
    </div>
</div>

<div style="max-width:860px;">
    <form action="{{ route('parts.store') }}" method="POST">
        @csrf

        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-cubes" style="color:var(--accent-2);margin-right:8px;"></i>Informasi Part</span>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Part No. <span class="required">*</span></label>
                        <input type="text" name="part_no" class="form-control" value="{{ old('part_no') }}" placeholder="Contoh: 2PV-F1585-00" required>
                        @error('part_no') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Part Name <span class="required">*</span></label>
                        <input type="text" name="part_name" class="form-control" value="{{ old('part_name') }}" placeholder="Nama part / produk" required>
                        @error('part_name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Customer</label>
                        <select name="m_customer_id" class="form-control">
                            <option value="">-- Pilih Customer --</option>
                            @foreach($customers ?? [] as $c)
                                <option value="{{ $c->id }}" {{ old('m_customer_id') == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('m_customer_id') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Panjang Part <span style="font-size:11px;color:var(--text-dim);">(mm)</span></label>
                        <input type="number" name="panjang_part" id="panjangPart" class="form-control"
                               value="{{ old('panjang_part') }}" step="0.01" min="0" placeholder="47.6">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">B/Q <span style="font-size:11px;color:var(--text-dim);">(Bisa dikosongkan)</span></label>
                        <input type="number" name="bq" id="bqField" class="form-control"
                               value="{{ old('bq') }}" step="0.0001" min="0" placeholder="0">
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
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Part</button>
            <a href="{{ route('parts.index') }}" class="btn btn-ghost"><i class="fas fa-times"></i> Batal</a>
        </div>
    </form>
</div>
@endsection
