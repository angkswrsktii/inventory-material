@extends('layouts.app')

@section('title', 'Edit User')
@section('topbar-title', 'Edit User')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('users.index') }}">Manajemen User</a>
    <span class="sep">/</span>
    <span>Edit User</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">Edit User: {{ $user->name }}</div>
        <div class="page-subtitle">Ubah data akun pengguna</div>
    </div>
</div>

<div style="max-width: 560px;">
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-pen" style="color:var(--accent);margin-right:8px;"></i>Edit Akun</span>
            @php
                $editRoleConfig = match($user->role) {
                    'pimpinan'      => ['icon' => 'fa-user-tie',      'label' => 'Pimpinan',      'style' => 'background:rgba(79,142,247,0.1);color:var(--accent);'],
                    'kepala_gudang' => ['icon' => 'fa-warehouse',     'label' => 'Kepala Gudang', 'style' => 'background:rgba(16,185,129,0.1);color:#10b981;'],
                    'karyawan'      => ['icon' => 'fa-user-gear',     'label' => 'Pegawai',       'style' => 'background:rgba(124,107,239,0.1);color:var(--accent-2);'],
                    default         => ['icon' => 'fa-shield-halved', 'label' => 'Admin',         'style' => 'background:rgba(79,142,247,0.15);color:var(--accent);'],
                };
            @endphp
            <span class="badge" style="{{ $editRoleConfig['style'] }}">
                <i class="fas {{ $editRoleConfig['icon'] }}"></i> {{ $editRoleConfig['label'] }}
            </span>
        </div>
        <div class="card-body">
            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf @method('PUT')

                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name', $user->name) }}" required>
                    @error('name') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Email <span class="required">*</span></label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email', $user->email) }}" required>
                    @error('email') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Role <span class="required">*</span></label>
                    <select name="role" class="form-control" required
                            {{ ($user->id === auth()->id()) ? 'disabled' : '' }}>
                        <option value="pimpinan"      {{ old('role', $user->role) === 'pimpinan'      ? 'selected' : '' }}>👔 Pimpinan</option>
                        <option value="kepala_gudang" {{ old('role', $user->role) === 'kepala_gudang' ? 'selected' : '' }}>🏭 Kepala Gudang</option>
                        <option value="karyawan"      {{ old('role', $user->role) === 'karyawan'      ? 'selected' : '' }}>👷 Pegawai</option>
                        <option value="admin"         {{ old('role', $user->role) === 'admin'         ? 'selected' : '' }}>🛡️ Administrator</option>
                    </select>
                    @if($user->id === auth()->id())
                        <input type="hidden" name="role" value="{{ $user->role }}">
                        <div style="font-size:11px;color:var(--text-muted);margin-top:4px;">Tidak dapat mengubah role akun sendiri</div>
                    @endif
                    @error('role') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group" style="display:flex; align-items:center; gap:10px;">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                           {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                           {{ ($user->id === auth()->id()) ? 'disabled' : '' }}
                           style="width:16px;height:16px;cursor:pointer;">
                    <label for="is_active" style="cursor:pointer; font-size:13.5px; color:var(--text);">Akun Aktif</label>
                    @if($user->id === auth()->id())
                        <input type="hidden" name="is_active" value="1">
                    @endif
                </div>

                <div class="divider"></div>

                <div style="margin-bottom:14px;">
                    <div style="font-size:12px; color:var(--text-muted); margin-bottom:10px;">
                        <i class="fas fa-lock"></i> Kosongkan jika tidak ingin mengubah password
                    </div>
                    <div class="form-row">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter">
                            @error('password') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                        </div>
                    </div>
                </div>

                <div class="divider"></div>

                <div style="display:flex; gap:10px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-ghost">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection