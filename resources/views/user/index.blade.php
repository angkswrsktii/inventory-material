@extends('layouts.app')

@section('title', 'Manajemen User')
@section('topbar-title', 'Manajemen User')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Manajemen User</div>
        <div class="page-subtitle">Kelola akun pengguna sistem — Admin & Karyawan</div>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="fas fa-user-plus"></i> Tambah User
    </a>
</div>

<!-- Info Box -->
<div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:20px;">
    <div style="background:var(--accent-glow); border:1px solid rgba(79,142,247,0.2); border-radius:var(--radius); padding:16px 20px; display:flex; align-items:center; gap:14px;">
        <div style="width:40px;height:40px;background:var(--accent);border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;font-size:16px;flex-shrink:0;">
            <i class="fas fa-shield-halved"></i>
        </div>
        <div>
            <div style="font-family:'Syne',sans-serif;font-weight:700;color:var(--text);font-size:14px;">Role Admin</div>
            <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">Akses penuh: CRUD material, stok, pengambilan, <strong style="color:var(--accent)">manajemen user</strong>, laporan</div>
        </div>
    </div>
    <div style="background:rgba(124,107,239,0.08); border:1px solid rgba(124,107,239,0.2); border-radius:var(--radius); padding:16px 20px; display:flex; align-items:center; gap:14px;">
        <div style="width:40px;height:40px;background:var(--accent-2);border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;font-size:16px;flex-shrink:0;">
            <i class="fas fa-user-gear"></i>
        </div>
        <div>
            <div style="font-family:'Syne',sans-serif;font-weight:700;color:var(--text);font-size:14px;">Role Karyawan</div>
            <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">Akses terbatas: input stok masuk, kartu pengambilan, lihat laporan</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title">
            <i class="fas fa-users" style="color:var(--accent);margin-right:8px;"></i>
            Daftar User <span style="font-size:12px;color:var(--text-muted);font-weight:400;margin-left:8px;">{{ $users->total() }} pengguna</span>
        </span>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div style="width:34px;height:34px;border-radius:50%;background:{{ $user->isAdmin() ? 'linear-gradient(135deg,var(--accent),var(--accent-2))' : 'linear-gradient(135deg,var(--accent-2),#9b59b6)' }};display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:white;flex-shrink:0;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:500;">{{ $user->name }}</div>
                                @if($user->id === auth()->id())
                                    <div style="font-size:10px;color:var(--accent);">● Anda</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td style="color:var(--text-muted); font-size:13px;">{{ $user->email }}</td>
                    <td>
                        @if($user->isAdmin())
                            <span class="badge badge-info"><i class="fas fa-shield-halved"></i> Admin</span>
                        @else
                            <span class="badge" style="background:rgba(124,107,239,0.1);color:var(--accent-2);">
                                <i class="fas fa-user-gear"></i> Karyawan
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($user->is_active)
                            <span class="badge badge-success"><i class="fas fa-circle" style="font-size:8px;"></i> Aktif</span>
                        @else
                            <span class="badge badge-danger"><i class="fas fa-circle" style="font-size:8px;"></i> Nonaktif</span>
                        @endif
                    </td>
                    <td style="font-size:12px;color:var(--text-muted);">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="text-center">
                        <div style="display:flex; gap:6px; justify-content:center; flex-wrap:wrap;">
                            <!-- Toggle Active -->
                            @if($user->id !== auth()->id())
                                <form action="{{ route('users.toggle-active', $user) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-xs {{ $user->is_active ? 'btn-ghost' : 'btn-success' }}"
                                            title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <i class="fas {{ $user->is_active ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                        {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('users.edit', $user) }}" class="btn btn-ghost btn-xs">
                                <i class="fas fa-pen"></i> Edit
                            </a>

                            @if($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user) }}" method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="fas fa-users"></i>
                            <h4>Belum Ada User</h4>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="pagination-wrap">
        <div class="pagination-info">{{ $users->total() }} pengguna</div>
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection