@extends('layouts.app')

@section('title', 'Inventory Stock')
@section('topbar-title', 'Inventory Stock')

@section('topbar-actions')
    <span style="font-size:12px; color: var(--text-muted);">
        <i class="fas fa-clock"></i>
        {{ now()->format('d M Y, H:i') }}
    </span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Inventory Stock</div>
        <div class="page-subtitle">Daftar stok material dan part di setiap gudang</div>
    </div>
</div>

<!-- Filter -->
<div class="card" style="margin-bottom: 20px;">
    <div class="card-body" style="padding: 16px 20px;">
        <form method="GET" action="{{ route('inventory-stocks.index') }}" style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
            <div style="flex:1; min-width:200px; position:relative;">
                <i class="fas fa-search" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:13px;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama material / part..."
                    style="width:100%; background:var(--surface-2); border:1px solid var(--border); color:var(--text); padding:8px 12px 8px 34px; border-radius:var(--radius-sm); font-family:inherit; font-size:13px; outline:none;">
            </div>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Cari</button>
            @if(request('search'))
                <a href="{{ route('inventory-stocks.index') }}" class="btn btn-ghost btn-sm"><i class="fas fa-times"></i> Reset</a>
            @endif
        </form>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th>Item</th>
                    <th>Tipe</th>
                    <th>Gudang</th>
                    <th class="text-right">Min. Stok</th>
                    <th class="text-right">Maks. Stok</th>
                    <th class="text-right">Stok Saat Ini</th>
                    <th>Status</th>
                    <th width="110">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stocks as $stock)
                <tr>
                    <td style="color:var(--text-muted);">{{ $stocks->firstItem() + $loop->index }}</td>
                    <td>
                        <div style="font-weight:500;">{{ $stock->material->name ?? $stock->part->part_name ?? '-' }}</div>
                        <div style="font-size:11px; color:var(--text-muted);">
                            {{ $stock->material->code ?? $stock->part->part_no ?? '-' }}
                        </div>
                    </td>
                    <td>
                        @if($stock->m_material_id)
                            <span class="badge" style="background:var(--surface-2); color:var(--text); border:1px solid var(--border);">Material</span>
                        @elseif($stock->m_part_id)
                            <span class="badge" style="background:var(--surface-2); color:var(--text); border:1px solid var(--border);">Part</span>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $stock->warehouse->name ?? '-' }}</td>
                    <td class="text-right" style="color:var(--text-muted);">{{ number_format($stock->minimum_stock, 2) }}</td>
                    <td class="text-right" style="color:var(--text-muted);">{{ number_format($stock->max_stock, 2) }}</td>
                    <td class="text-right" style="font-weight:600; color: {{ $stock->current_stock <= 0 ? 'var(--danger)' : ($stock->current_stock <= $stock->minimum_stock ? 'var(--warning)' : 'var(--success)') }}">
                        {{ number_format($stock->current_stock, 2) }}
                    </td>
                    <td>
                        @if($stock->current_stock <= 0)
                            <span class="badge badge-danger"><i class="fas fa-ban fa-xs"></i> Kosong</span>
                        @elseif($stock->current_stock <= $stock->minimum_stock)
                            <span class="badge badge-warning"><i class="fas fa-triangle-exclamation fa-xs"></i> Rendah</span>
                        @else
                            <span class="badge badge-success"><i class="fas fa-check fa-xs"></i> Normal</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('inventory-stocks.show', $stock) }}" class="btn btn-ghost btn-sm" title="Detail Mutasi">
                            <i class="fas fa-list"></i> Mutasi
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-state" style="padding: 60px 20px;">
                            <i class="fas fa-boxes-stacked"></i>
                            <h4>Belum Ada Stok Tersedia</h4>
                            <p>Stok akan terbentuk otomatis ketika ada penerimaan barang atau pengeluaran barang.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($stocks->hasPages())
    <div style="padding: 16px 20px; border-top: 1px solid var(--border); display:flex; justify-content:space-between; align-items:center;">
        <div style="font-size:13px; color:var(--text-muted);">
            Menampilkan {{ $stocks->firstItem() }}–{{ $stocks->lastItem() }} dari {{ $stocks->total() }} stok
        </div>
        <div style="display:flex; gap:6px;">
            @if($stocks->onFirstPage())
                <span class="btn btn-ghost btn-sm" style="opacity:0.4; cursor:not-allowed;"><i class="fas fa-chevron-left"></i></span>
            @else
                <a href="{{ $stocks->previousPageUrl() }}" class="btn btn-ghost btn-sm"><i class="fas fa-chevron-left"></i></a>
            @endif
            @foreach($stocks->getUrlRange(max(1, $stocks->currentPage()-2), min($stocks->lastPage(), $stocks->currentPage()+2)) as $page => $url)
                <a href="{{ $url }}" class="btn btn-sm {{ $page == $stocks->currentPage() ? 'btn-primary' : 'btn-ghost' }}">{{ $page }}</a>
            @endforeach
            @if($stocks->hasMorePages())
                <a href="{{ $stocks->nextPageUrl() }}" class="btn btn-ghost btn-sm"><i class="fas fa-chevron-right"></i></a>
            @else
                <span class="btn btn-ghost btn-sm" style="opacity:0.4; cursor:not-allowed;"><i class="fas fa-chevron-right"></i></span>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
