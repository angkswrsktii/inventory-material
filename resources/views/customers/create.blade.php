@extends('layouts.app')
@section('title', 'Tambah Customer')
@section('topbar-title', 'Tambah Customer')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('customers.index') }}">Data Customer</a>
    <span class="sep">/</span>
    <span>Tambah Customer</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">Tambah Customer Baru</div>
        <div class="page-subtitle">Isi form berikut untuk mendaftarkan pelanggan baru</div>
    </div>
</div>

<div style="max-width:860px;">
    <form action="{{ route('customers.store') }}" method="POST">
        @csrf

        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-user-tie" style="color:var(--accent-2);margin-right:8px;"></i>Informasi Pelanggan</span>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nama Perusahaan / Pelanggan <span class="required">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="PT. Nama Customer" required>
                        @error('name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kontak Person (PIC)</label>
                        <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person') }}" placeholder="Nama PIC">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="0812...">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="email@perusahaan.com">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Alamat Lengkap</label>
                    <textarea name="address" class="form-control" rows="2" placeholder="Jl. Raya ...">{{ old('address') }}</textarea>
                </div>
            </div>
        </div>

        <div style="display:flex; gap:10px;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Customer</button>
            <a href="{{ route('customers.index') }}" class="btn btn-ghost"><i class="fas fa-times"></i> Batal</a>
        </div>
    </form>
</div>
@endsection
