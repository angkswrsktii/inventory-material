@extends('layouts.app')
@section('title', 'Tambah PIC')
@section('topbar-title', 'Tambah PIC')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('pics.index') }}">Data PIC</a>
    <span class="sep">/</span>
    <span>Tambah PIC</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">Tambah PIC Baru</div>
        <div class="page-subtitle">Isi form berikut untuk menambahkan Person In Charge baru</div>
    </div>
</div>

<div style="max-width:600px;">
    <form action="{{ route('pics.store') }}" method="POST">
        @csrf

        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-user-tag" style="color:var(--accent-2);margin-right:8px;"></i>Informasi PIC</span>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="John Doe" required>
                    @error('name') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Posisi / Bagian</label>
                    <input type="text" name="position" class="form-control" value="{{ old('position') }}" placeholder="Operator Produksi, Staff Gudang...">
                    @error('position') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div style="display:flex; gap:10px;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan PIC</button>
            <a href="{{ route('pics.index') }}" class="btn btn-ghost"><i class="fas fa-times"></i> Batal</a>
        </div>
    </form>
</div>
@endsection
