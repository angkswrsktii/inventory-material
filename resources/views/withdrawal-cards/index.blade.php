@extends('layouts.app')
@section('title', 'Kartu Pengambilan')
@section('topbar-title', 'Kartu Pengambilan')

@section('topbar-actions')
    <a href="{{ route('withdrawal-cards.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Buat Pengambilan
    </a>
@endsection

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Kartu Pengambilan Material</div>
        <div class="page-subtitle">Dokumen pengeluaran raw material ke lantai produksi</div>
    </div>
    <a href="{{ route('withdrawal-cards.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Buat Pengambilan
    </a>
</div>

<div class="card">
    <div class="card-header" style="flex-wrap:wrap; gap:12px;">
        <form method="GET" class="search-bar" style="flex:1;">
            <div class="search-input-wrap">
                <i class="fas fa-search"></i>
                <input type="text" name="search" class="form-control"
                       placeholder="Cari no. dokumen, PIC, line, part..." value="{{ request('search') }}">
            </div>
            <input type="date" name="date_from" class="form-control" style="width:155px;" value="{{ request('date_from') }}">
            <input type="date" name="date_to"   class="form-control" style="width:155px;" value="{{ request('date_to') }}">
            <button type="submit" class="btn btn-secondary"><i class="fas fa-filter"></i> Filter</button>
            @if(request()->hasAny(['search','date_from','date_to','status']))
                <a href="{{ route('withdrawal-cards.index') }}" class="btn btn-ghost"><i class="fas fa-times"></i></a>
            @endif
        </form>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No. Dokumen</th>
                    <th>Tanggal</th>
                    <th>PIC</th>
                    <th>Line</th>
                    <th>Part Name</th>
                    <th>Work Order</th>
                    <th>Jml Item</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($withdrawals as $w)
                <tr>
                    <td>
                        <span class="mono" style="color:var(--accent); font-size:12px;">{{ $w->document_no }}</span>
                    </td>
                    <td style="white-space:nowrap; font-size:12px; color:var(--text-muted);">
                        {{ $w->withdrawal_date->format('d M Y') }}
                    </td>
                    <td style="font-weight:500;">{{ $w->pic }}</td>
                    <td><span class="badge badge-muted">{{ $w->line }}</span></td>
                    <td>{{ $w->part_name }}</td>
                    <td style="font-size:12.5px; color:var(--text-muted);">{{ $w->work_order ?: '—' }}</td>
                    <td class="text-center">
                        <span class="badge badge-info">{{ $w->items->count() }} item</span>
                    </td>
                    <td>
                        @if($w->status === 'approved')
                            <span class="badge badge-success"><i class="fas fa-check"></i> Approved</span>
                        @elseif($w->status === 'pending')
                            <span class="badge badge-warning"><i class="fas fa-clock"></i> Pending</span>
                        @else
                            <span class="badge badge-danger"><i class="fas fa-times"></i> Rejected</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div style="display:flex; gap:6px; justify-content:center;">
                            <a href="{{ route('withdrawal-cards.show', $w) }}" class="btn btn-ghost btn-xs" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('reports.print.withdrawal', $w) }}" target="_blank" class="btn btn-ghost btn-xs" title="Print">
                                <i class="fas fa-print"></i>
                            </a>
                            @if(auth()->user()?->isAdmin() && $w->status !== 'approved')
                            <form action="{{ route('withdrawal-cards.destroy', $w) }}" method="POST"
                                  onsubmit="return confirm('Yakin hapus dokumen ini?')">
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
                    <td colspan="9">
                        <div class="empty-state">
                            <i class="fas fa-file-invoice"></i>
                            <h4>Belum Ada Kartu Pengambilan</h4>
                            <p>Buat kartu pengambilan pertama Anda</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($withdrawals->hasPages())
    <div class="pagination-wrap">
        <div class="pagination-info">
            Menampilkan {{ $withdrawals->firstItem() }}–{{ $withdrawals->lastItem() }} dari {{ $withdrawals->total() }}
        </div>
        {{ $withdrawals->links() }}
    </div>
    @endif
</div>
@endsection