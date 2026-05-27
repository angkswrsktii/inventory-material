@extends('layouts.app')
@section('title', __('app.project.add'))
@section('topbar-title', __('app.nav.master_data') . ' — ' . __('app.nav.data_project'))

@section('content')
<div class="breadcrumb">
    <a href="{{ route('projects.index') }}">Data Project</a>
    <span class="sep">/</span>
    <span>{{ __("app.btn.add") }}</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">{{ __("app.project.create_title") }}</div>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <form action="{{ route('projects.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Nama Project <span class="required">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Masukkan nama project..." required autocomplete="off">
                @error('name') 
                    <div class="form-error" style="color:var(--danger); font-size:12px; margin-top:4px;">{{ $message }}</div> 
                @enderror
            </div>

            <div style="display:flex; gap:10px; margin-top:24px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ __("app.btn.save") }}
                </button>
                <a href="{{ route('projects.index') }}" class="btn btn-ghost">{{ __("app.btn.cancel") }}</a>
            </div>
        </form>
    </div>
</div>
@endsection