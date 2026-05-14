@extends('layouts.app')
@section('title', 'Edit PIC')
@section('topbar-title', 'Edit PIC')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('pics.index') }}">Data PIC</a>
    <span class="sep">/</span>
    <span>Edit: {{ $pic->name }}</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">Edit PIC</div>
        <div class="page-subtitle">Perbarui data Person In Charge</div>
    </div>
</div>

<div style="max-width:600px;">
    <form action="{{ route('pics.update', $pic) }}" method="POST">
        @csrf @method('PUT')

        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-user-tag" style="color:var(--accent-2);margin-right:8px;"></i>Informasi PIC</span>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $pic->name) }}" required>
                    @error('name') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Posisi / Bagian</label>
                    <input type="text" name="position" class="form-control" value="{{ old('position', $pic->position) }}">
                    @error('position') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div style="display:flex; gap:10px;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
            <a href="{{ route('pics.index') }}" class="btn btn-ghost"><i class="fas fa-times"></i> Batal</a>
        </div>
    </form>
</div>
@endsection
