@extends('layouts.app')
@section('title', 'Tambah Customer')
@section('topbar-title', 'Master Data')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('customers.index') }}">Data Customer</a>
    <span class="sep">/</span>
    <span>Tambah Customer</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">Tambah Customer Baru</div>
        <div class="page-subtitle">Isi data customer / pelanggan</div>
    </div>
</div>

<div style="max-width:680px;">
    <form action="{{ route('customers.store') }}" method="POST">
        @csrf

        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-user" style="color:var(--accent);margin-right:8px;"></i>Informasi Customer</span>
            </div>
            <div class="card-body">

                <div class="form-group">
                    <label class="form-label">Nama Customer <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name') }}" placeholder="Nama perusahaan atau pelanggan" required>
                    @error('name') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Kontak Person</label>
                    <input type="text" name="contact_person" class="form-control"
                           value="{{ old('contact_person') }}" placeholder="Nama PIC dari customer">
                    @error('contact_person') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Telepon</label>
                        <input type="text" name="phone" class="form-control"
                               value="{{ old('phone') }}" placeholder="Nomor telepon">
                        @error('phone') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email') }}" placeholder="Alamat email">
                        @error('email') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Alamat</label>
                    <textarea name="address" class="form-control" rows="3"
                              placeholder="Alamat lengkap customer">{{ old('address') }}</textarea>
                    @error('address') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">NPWP</label>
                    <input type="text" name="npwp" class="form-control"
                           value="{{ old('npwp') }}" placeholder="Nomor NPWP (opsional)">
                    @error('npwp') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control" rows="2"
                              placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
                    @error('notes') <div class="form-error">{{ $message }}</div> @enderror
                </div>

            </div>
        </div>

        <div style="display:flex;gap:10px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Customer
            </button>
            <a href="{{ route('customers.index') }}" class="btn btn-ghost">Batal</a>
        </div>
    </form>
</div>
@endsection
