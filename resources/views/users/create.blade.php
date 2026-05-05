@extends('layouts.app')

@section('title', 'Tambah User')
@section('topbar-title', 'Tambah User')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('users.index') }}">Manajemen User</a>
    <span class="sep">/</span>
    <span>Tambah User</span>
</div>

<div class="page-header">
    <div>
        <div class="page-title">Tambah User Baru</div>
        <div class="page-subtitle">Buat akun untuk Pimpinan, Kepala Gudang, atau Pegawai</div>
    </div>
</div>

<div style="max-width: 560px;">
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-user-plus" style="color:var(--accent);margin-right:8px;"></i>Form User Baru</span>
        </div>
        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                           placeholder="Masukkan nama lengkap" required>
                    @error('name') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Email <span class="required">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                           placeholder="email@perusahaan.com" required>
                    @error('email') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Role <span class="required">*</span></label>
                    <select name="role" class="form-control" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="pimpinan"      {{ old('role') === 'pimpinan'      ? 'selected' : '' }}>
                            👔 Pimpinan — Akses penuh & approval purchase order
                        </option>
                        <option value="kepala_gudang" {{ old('role') === 'kepala_gudang' ? 'selected' : '' }}>
                            🏭 Kepala Gudang — Kelola stok & purchase request/order
                        </option>
                        <option value="karyawan"      {{ old('role') === 'karyawan'      ? 'selected' : '' }}>
                            👷 Pegawai — Input penerimaan & permintaan barang
                        </option>
                        <option value="admin"         {{ old('role') === 'admin'         ? 'selected' : '' }}>
                            🛡️ Administrator — Akses penuh sistem
                        </option>
                    </select>
                    @error('role') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Password <span class="required">*</span></label>
                        <input type="password" name="password" class="form-control"
                               placeholder="Minimal 6 karakter" required>
                        @error('password') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password <span class="required">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control"
                               placeholder="Ulangi password" required>
                    </div>
                </div>

                <div class="form-group" style="display:flex; align-items:center; gap:10px;">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                           {{ old('is_active', '1') ? 'checked' : '' }}
                           style="width:16px;height:16px;cursor:pointer;">
                    <label for="is_active" style="cursor:pointer; font-size:13.5px; color:var(--text);">
                        User langsung aktif setelah dibuat
                    </label>
                </div>

                <div class="divider"></div>

                <div style="display:flex; gap:10px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Buat User
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-ghost">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Role Info -->
    <div style="margin-top:16px; display:grid; gap:10px;">
        <div style="background:var(--surface); border:1px solid var(--border); border-radius:var(--radius-sm); padding:14px 16px;">
            <div style="font-weight:600; color:var(--accent); margin-bottom:6px; font-size:13px;">
                <i class="fas fa-user-tie"></i> Hak Akses Pimpinan
            </div>
            <ul style="font-size:12px; color:var(--text-muted); list-style:none; display:flex; flex-wrap:wrap; gap:6px;">
                @foreach(['Dashboard','Data Material','Kartu Stok','Kartu Pengambilan','Laporan','Manajemen User','Approval Purchase Order','Approval Permintaan Barang'] as $f)
                    <li style="background:var(--accent-glow);color:var(--accent);padding:2px 10px;border-radius:20px;">✓ {{ $f }}</li>
                @endforeach
            </ul>
        </div>
        <div style="background:var(--surface); border:1px solid var(--border); border-radius:var(--radius-sm); padding:14px 16px;">
            <div style="font-weight:600; color:#10b981; margin-bottom:6px; font-size:13px;">
                <i class="fas fa-warehouse"></i> Hak Akses Kepala Gudang
            </div>
            <ul style="font-size:12px; color:var(--text-muted); list-style:none; display:flex; flex-wrap:wrap; gap:6px;">
                @foreach(['Dashboard','Data Material','Kartu Stok','Penerimaan Barang','Purchase Request','Purchase Order','Approval Permintaan Barang'] as $f)
                    <li style="background:rgba(16,185,129,0.1);color:#10b981;padding:2px 10px;border-radius:20px;">✓ {{ $f }}</li>
                @endforeach
                <li style="background:var(--danger-bg);color:var(--danger);padding:2px 10px;border-radius:20px;">✗ Manajemen User</li>
            </ul>
        </div>
        <div style="background:var(--surface); border:1px solid var(--border); border-radius:var(--radius-sm); padding:14px 16px;">
            <div style="font-weight:600; color:var(--accent-2); margin-bottom:6px; font-size:13px;">
                <i class="fas fa-user-gear"></i> Hak Akses Pegawai
            </div>
            <ul style="font-size:12px; color:var(--text-muted); list-style:none; display:flex; flex-wrap:wrap; gap:6px;">
                @foreach(['Dashboard','Input Penerimaan Barang','Permintaan Barang','Lihat Status Persetujuan'] as $f)
                    <li style="background:rgba(124,107,239,0.1);color:var(--accent-2);padding:2px 10px;border-radius:20px;">✓ {{ $f }}</li>
                @endforeach
                <li style="background:var(--danger-bg);color:var(--danger);padding:2px 10px;border-radius:20px;">✗ Manajemen User</li>
                <li style="background:var(--danger-bg);color:var(--danger);padding:2px 10px;border-radius:20px;">✗ Purchase Order</li>
            </ul>
        </div>
    </div>
</div>
@endsection