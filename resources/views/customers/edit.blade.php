@extends('layouts.app')
@section('title', 'Edit Customer')
@section('topbar-title', __('app.nav.master_data') . ' — ' . __('app.nav.data_customer'))

@section('content')
<div class="breadcrumb">
    <a href="{{ route('customers.index') }}">Data Customer</a>
    <span class="sep">/</span>
    <span>Edit: {{ $customer->name }}</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">Edit Customer</div>
        <div class="page-subtitle">Perbarui data pelanggan</div>
    </div>
</div>

<div style="max-width:860px;">
    <form action="{{ route('customers.update', $customer) }}" method="POST">
        @csrf @method('PUT')

        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-user-tie" style="color:var(--accent-2);margin-right:8px;"></i>Informasi Pelanggan</span>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nama Perusahaan / Pelanggan <span class="required">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}" required>
                        @error('name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('app.supplier.contact_person') }} (PIC)</label>
                        <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person', $customer->contact_person) }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">{{ __('app.supplier.phone') }}</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('app.supplier.email') }}</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('app.supplier.address') }}</label>
                    <textarea name="address" class="form-control" rows="2">{{ old('address', $customer->address) }}</textarea>
                </div>
            </div>
        </div>

        </div>

        <div style="display:flex; gap:10px;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> {{ __('app.btn.save_changes') }}</button>
            <a href="{{ route('customers.index') }}" class="btn btn-ghost"><i class="fas fa-times"></i> {{ __("app.btn.cancel") }}</a>
        </div>
    </form>
</div>
@endsection
