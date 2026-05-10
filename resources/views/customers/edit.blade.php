@extends('layouts.app')
@section('title', 'Edit Customer')
@section('topbar-title', 'Master Data')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('customers.index') }}">Data Customer</a>
    <span class="sep">/</span>
    <span>Edit Customer</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">Edit Customer</div>
        <div class="page-subtitle">{{ $customer->name }}</div>
    </div>
</div>

<div style="max-width:680px;">
    <form action="{{ route('customers.update', $customer) }}" method="POST">
        @csrf @method('PUT')

        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-user" style="color:var(--accent);margin-right:8px;"></i>Informasi Customer</span>
            </div>
            <div class="card-body">

                <div class="form-group">
                    <label class="form-label">Nama Customer <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name', $customer->name) }}" required>
                    @error('name') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Kontak Person</label>
                    <input type="text" name="contact_person" class="form-control"
                           value="{{ old('contact_person', $customer->contact_person) }}">
                    @error('contact_person') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Telepon</label>
                        <input type="text" name="phone" class="form-control"
                               value="{{ old('phone', $customer->phone) }}">
                        @error('phone') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email', $customer->email) }}">
                        @error('email') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Alamat</label>
                    <textarea name="address" class="form-control" rows="3">{{ old('address', $customer->address) }}</textarea>
                    @error('address') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">NPWP</label>
                    <input type="text" name="npwp" class="form-control"
                           value="{{ old('npwp', $customer->npwp) }}">
                    @error('npwp') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes', $customer->notes) }}</textarea>
                    @error('notes') <div class="form-error">{{ $message }}</div> @enderror
                </div>

            </div>
        </div>

        <div style="display:flex;gap:10px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Customer
            </button>
            <a href="{{ route('customers.show', $customer) }}" class="btn btn-ghost">Batal</a>
        </div>
    </form>
</div>
@endsection
