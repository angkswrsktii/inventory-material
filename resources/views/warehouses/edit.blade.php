@extends('layouts.app')
@section('title', 'Edit Gudang')
@section('topbar-title', __('app.nav.master_data') . ' — ' . __('app.nav.data_warehouse'))

@section('content')
<div class="breadcrumb">
    <a href="{{ route('warehouses.index') }}">{{ __('app.nav.data_warehouse') }}</a>
    <span class="sep">/</span>
    <span>Edit: {{ $warehouse->name }}</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">Edit {{ __('app.warehouse.title') }}</div>
        <div class="page-subtitle">Perbarui gudang</div>
    </div>
</div>

<div style="max-width:720px;">
    <form action="{{ route('warehouses.update', $warehouse) }}" method="POST">
        @csrf @method('PUT')

        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-warehouse" style="color:var(--accent-2);margin-right:8px;"></i>{{ __("app.warehouse.info") }}</span>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">{{ __("app.warehouse.code") }} <span class="required">*</span></label>
                        <input type="text" name="code" class="form-control"
                            value="{{ old('code', $warehouse->code) }}"
                            style="text-transform:uppercase;" required>
                        <div class="form-hint">Kode unik untuk identifikasi gudang (maks. 20 karakter)</div>
                        @error('code') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __("app.warehouse.name") }} <span class="required">*</span></label>
                        <input type="text" name="name" class="form-control"
                            value="{{ old('name', $warehouse->name) }}" required>
                        @error('name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Lokasi / Keterangan</label>
                    <textarea name="location" class="form-control" rows="2">{{ old('location', $warehouse->location) }}</textarea>
                    @error('location') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div style="display:flex; gap:10px;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> {{ __('app.btn.save_changes') }}</button>
            <a href="{{ route('warehouses.index') }}" class="btn btn-ghost"><i class="fas fa-times"></i> {{ __("app.btn.cancel") }}</a>
        </div>
    </form>
</div>
@endsection
