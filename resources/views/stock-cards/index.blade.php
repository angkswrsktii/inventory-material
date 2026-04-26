@extends('layouts.app')
@section('title', 'Kartu Stok')
@section('topbar-title', 'Kartu Stok')
@section('topbar-actions')
    <span style="font-size:12px; color: var(--text-muted);">
        <i class="fas fa-clock"></i>
        {{ now()->format('d M Y, H:i') }}
    </span>
@endsection

@section('content')
<div style="margin-bottom:24px;">
    <div class="page-title">Kartu Stok</div>
    <div class="page-subtitle" style="margin-bottom:14px;">Riwayat semua transaksi masuk dan keluar raw material</div>
    <div style="display:flex; gap:10px;">
        <a href="{{ route('withdrawal-cards.create') }}" class="btn btn-secondary">
            <i class="fas fa-file-invoice"></i> Buat Pengambilan
        </a>
        <a href="{{ route('stock-cards.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Input Penerimaan
        </a>
    </div>
</div>

<div class="card">
    <!-- Filter -->
    <div class="card-header">
        <form method="GET" style="display:flex; gap:10px; flex-wrap:wrap; width:100%;">
            <div class="search-input-wrap" style="flex:1; min-width:180px;">
                <i class="fas fa-search"></i>
                <input type="text" name="search" class="form-control"
                       placeholder="Cari material..." value="{{ request('search') }}">
            </div>
            <select name="material_id" class="form-control" style="width:180px;">
                <option value="">Semua Material</option>
                @foreach($materials as $m)
                    <option value="{{ $m->id }}" {{ request('material_id') == $m->id ? 'selected' : '' }}>
                        {{ $m->name }}
                    </option>
                @endforeach
            </select>
            <select name="type" class="form-control" style="width:130px;">
                <option value="">Semua Tipe</option>
                <option value="in"  {{ request('type') === 'in'  ? 'selected' : '' }}>Masuk</option>
                <option value="out" {{ request('type') === 'out' ? 'selected' : '' }}>Keluar</option>
            </select>
            <input type="date" name="date_from" class="form-control" style="width:150px;" value="{{ request('date_from') }}">
            <input type="date" name="date_to"   class="form-control" style="width:150px;" value="{{ request('date_to') }}">
            <button type="submit" class="btn btn-secondary">
                <i class="fas fa-filter"></i> Filter
            </button>
            @if(request()->hasAny(['search','material_id','type','date_from','date_to']))
                <a href="{{ route('stock-cards.index') }}" class="btn btn-ghost">
                    <i class="fas fa-times"></i> Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kode</th>
                    <th>Nama Material</th>
                    <th>Tipe</th>
                    <th>Keterangan / Sumber</th>
                    <th>No. Referensi</th>
                    <th class="text-right">Masuk</th>
                    <th class="text-right">Keluar</th>
                    <th class="text-right">Saldo</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stockCards as $sc)
                <tr>
                    <td style="white-space:nowrap; font-size:12px; color:var(--text-muted);">
                        {{ $sc->transaction_date->format('d M Y') }}
                    </td>
                    <td>
                        <span class="mono" style="color:var(--accent); font-size:11px;">
                            {{ $sc->material->code ?? '-' }}
                        </span>
                    </td>
                    <td style="font-weight:500;">{{ $sc->material->name ?? '-' }}</td>
                    <td>
                        @if($sc->type === 'in')
                            <span class="badge badge-in"><i class="fas fa-arrow-down fa-xs"></i> Masuk</span>
                        @else
                            <span class="badge badge-out"><i class="fas fa-arrow-up fa-xs"></i> Keluar</span>
                        @endif
                    </td>
                    <td style="font-size:12.5px; color:var(--text-muted);">{{ $sc->source ?: '-' }}</td>
                    <td>
                        @if($sc->reference_no)
                            <span class="mono" style="color:var(--accent); font-size:11px;">{{ $sc->reference_no }}</span>
                        @else
                            <span style="color:var(--text-dim);">—</span>
                        @endif
                    </td>
                    <td class="text-right">
                        @if($sc->quantity_in > 0)
                            <span class="stock-in">+{{ number_format($sc->quantity_in, 2) }}</span>
                        @else
                            <span style="color:var(--text-dim);">—</span>
                        @endif
                    </td>
                    <td class="text-right">
                        @if($sc->quantity_out > 0)
                            <span class="stock-out">-{{ number_format($sc->quantity_out, 2) }}</span>
                        @else
                            <span style="color:var(--text-dim);">—</span>
                        @endif
                    </td>
                    <td class="text-right" style="font-weight:600;">
                        {{ number_format($sc->balance, 2) }}
                    </td>
                    <td class="text-center">
                        <a href="{{ route('stock-cards.show', $sc->material) }}"
                           class="btn btn-ghost btn-xs" title="Lihat Kartu Stok">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10">
                        <div class="empty-state">
                            <i class="fas fa-table-list"></i>
                            <h4>Belum Ada Transaksi</h4>
                            <p>Mulai dengan input penerimaan barang</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($stockCards->hasPages())
    <div class="pagination-wrap">
        <div class="pagination-info">
            Menampilkan {{ $stockCards->firstItem() }}–{{ $stockCards->lastItem() }}
            dari {{ $stockCards->total() }} transaksi
        </div>
        {{ $stockCards->links() }}
    </div>
    @endif
</div>
@endsection