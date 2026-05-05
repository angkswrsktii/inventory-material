@extends('layouts.app')
@section('title', isset($supplier) ? 'Edit Supplier' : 'Tambah Supplier')
@section('topbar-title', 'Master Data')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('suppliers.index') }}">Data Supplier</a>
    <span class="sep">/</span>
    <span>{{ isset($supplier) ? 'Edit: '.$supplier->name : 'Tambah Baru' }}</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">{{ isset($supplier) ? 'Edit Supplier' : 'Tambah Supplier' }}</div>
        <div class="page-subtitle">{{ isset($supplier) ? 'Perbarui data supplier' : 'Daftarkan supplier / vendor baru' }}</div>
    </div>
</div>

<form action="{{ isset($supplier) ? route('suppliers.update', $supplier) : route('suppliers.store') }}" method="POST">
    @csrf
    @if(isset($supplier)) @method('PUT') @endif

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; align-items:start;">

        {{-- Kiri --}}
        <div style="display:flex;flex-direction:column;gap:20px;">
            <div class="card">
                <div class="card-header">
                    <span class="card-title">
                        <i class="fas fa-building" style="color:var(--accent);margin-right:8px;"></i>
                        Informasi Utama
                    </span>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Nama Supplier <span class="required">*</span></label>
                        <input type="text" name="name" class="form-control"
                               value="{{ old('name', $supplier->name ?? '') }}"
                               placeholder="PT. Nama Perusahaan" required>
                        @error('name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kontak Person</label>
                        <input type="text" name="contact_person" class="form-control"
                               value="{{ old('contact_person', $supplier->contact_person ?? '') }}"
                               placeholder="Nama PIC di supplier">
                        @error('contact_person') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div class="form-group">
                            <label class="form-label">Telepon</label>
                            <input type="text" name="phone" class="form-control"
                                   value="{{ old('phone', $supplier->phone ?? '') }}"
                                   placeholder="0812...">
                            @error('phone') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="{{ old('email', $supplier->email ?? '') }}"
                                   placeholder="email@supplier.com">
                            @error('email') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" class="form-control" rows="3"
                                  placeholder="Alamat lengkap supplier...">{{ old('address', $supplier->address ?? '') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">NPWP</label>
                        <input type="text" name="npwp" class="form-control"
                               value="{{ old('npwp', $supplier->npwp ?? '') }}"
                               placeholder="00.000.000.0-000.000">
                    </div>
                </div>
            </div>
        </div>

        {{-- Kanan --}}
        <div style="display:flex;flex-direction:column;gap:20px;">
            <div class="card">
                <div class="card-header">
                    <span class="card-title">
                        <i class="fas fa-landmark" style="color:var(--accent-2);margin-right:8px;"></i>
                        Informasi Bank
                    </span>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Nama Bank</label>
                        <input type="text" name="bank_name" class="form-control"
                               value="{{ old('bank_name', $supplier->bank_name ?? '') }}"
                               placeholder="BCA, Mandiri, BNI...">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nomor Rekening</label>
                        <input type="text" name="bank_account" class="form-control"
                               value="{{ old('bank_account', $supplier->bank_account ?? '') }}"
                               placeholder="1234567890">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Atas Nama Rekening</label>
                        <input type="text" name="bank_account_name" class="form-control"
                               value="{{ old('bank_account_name', $supplier->bank_account_name ?? '') }}"
                               placeholder="Nama pemilik rekening">
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <span class="card-title">
                        <i class="fas fa-note-sticky" style="color:var(--warning);margin-right:8px;"></i>
                        Catatan
                    </span>
                </div>
                <div class="card-body">
                    <div class="form-group" style="margin-bottom:0;">
                        <textarea name="notes" class="form-control" rows="4"
                                  placeholder="Catatan tambahan tentang supplier ini...">{{ old('notes', $supplier->notes ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:10px;">
                <button type="submit" class="btn btn-primary" style="flex:1;">
                    <i class="fas fa-save"></i>
                    {{ isset($supplier) ? 'Simpan Perubahan' : 'Tambah Supplier' }}
                </button>
                <a href="{{ route('suppliers.index') }}" class="btn btn-ghost">Batal</a>
            </div>
        </div>
    </div>
</form>
@endsection