@extends('layouts.app')
@section('title', 'Edit Part')
@section('topbar-title', 'Edit Part')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('parts.index') }}">Data Part</a>
    <span class="sep">/</span>
    <span>Edit: {{ $part->part_name }}</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">Edit Part</div>
        <div class="page-subtitle">Perbarui data part</div>
    </div>
</div>

<div style="max-width:860px;">
    <form action="{{ route('parts.update', $part) }}" method="POST">
        @csrf @method('PUT')

        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-cubes" style="color:var(--accent-2);margin-right:8px;"></i>Informasi Part</span>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Part No. <span class="required">*</span></label>
                        <input type="text" name="part_no" class="form-control" value="{{ old('part_no', $part->part_no) }}" required>
                        @error('part_no') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Part Name <span class="required">*</span></label>
                        <input type="text" name="part_name" class="form-control" value="{{ old('part_name', $part->part_name) }}" required>
                        @error('part_name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Customer</label>
                        <select name="m_customer_id" class="form-control">
                            <option value="">-- Pilih Customer --</option>
                            @foreach($customers ?? [] as $c)
                                <option value="{{ $c->id }}" {{ old('m_customer_id', $part->m_customer_id) == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('m_customer_id') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Panjang Part <span style="font-size:11px;color:var(--text-dim);">(mm)</span></label>
                        <input type="number" name="panjang_part" class="form-control"
                               value="{{ old('panjang_part', $part->panjang_part) }}" step="0.01" min="0">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">B/Q</label>
                        <input type="number" name="bq" class="form-control"
                               value="{{ old('bq', $part->bq) }}" step="0.0001" min="0">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Keterangan</label>
                    <textarea name="description" class="form-control" rows="2">{{ old('description', $part->description) }}</textarea>
                </div>
                <div class="form-group">
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $part->is_active) ? 'checked' : '' }} style="width:16px;height:16px;">
                        <span class="form-label" style="margin:0;">Part Aktif</span>
                    </label>
                </div>
            </div>
        </div>

        <div style="display:flex; gap:10px;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
            <a href="{{ route('parts.index') }}" class="btn btn-ghost"><i class="fas fa-times"></i> Batal</a>
        </div>
    </form>
</div>
@endsection
